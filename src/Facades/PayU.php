<?php

namespace xGrz\PayU\Facades;

use xGrz\PayU\Api\Actions\AcceptPayment;
use xGrz\PayU\Api\Actions\CancelOrder;
use xGrz\PayU\Api\Actions\GetPaymentMethods;
use xGrz\PayU\Api\Actions\ShopBalance;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\ShopBalanceResponse;
use xGrz\PayU\Jobs\UpdatePayoutStatusJob;
use xGrz\PayU\Models\Payout;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Models\Transaction;

class PayU
{
    public static function accept(Transaction $transaction): bool
    {
        try {
            $accept = AcceptPayment::callApi($transaction);
            return $accept->isAccepted();
        } catch (PayUGeneralException $e) {
            return false;
        }
    }

    public static function reject(Transaction $transaction): bool
    {
        try {
            $rejected = CancelOrder::callApi($transaction);
            return $rejected->isCanceled();
        } catch (PayUGeneralException $e) {
            return false;
        }
    }

    public static function refund(Transaction $transaction, int|float $amount, string $description = null, string $backDescription = null, string $currencyCode = 'PLN'): bool
    {
        if (!$transaction->status->actionAvailable('refund')) return false;
        $transaction->refunds()->create([
            'amount' => $amount,
            'description' => $description,
            'bank_description' => $backDescription,
            'currency_code' => $currencyCode
        ]);
        return true;
    }

    public static function cancelRefund(Refund $refund): bool
    {
        if (!$refund->status->isDeletable()) return false;
        return (bool)$refund->delete();
    }

    public static function balance(): ?ShopBalanceResponse
    {
        try {
            return ShopBalance::callApi();
        } catch (PayUGeneralException $e) {
            return null;
        }
    }

    public static function payout(int|float $amount): bool
    {
        if (!Config::getShopId()) return false;

        try {
            return (bool)Payout::create(['amount' => $amount]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function payoutStatusCheck(Payout $payout, int $delay = null): void
    {
        if (is_null($delay)) $delay = Config::getPayoutInterval();

        dispatch(new UpdatePayoutStatusJob($payout))->delay($delay);

    }

    public static function cancelPayout(Payout $payout): bool
    {
        if (!$payout->status->actionAvailable('delete')) return false;

        $payout->delete();
        return true;
    }


    public static function getMethods(): array
    {
        try {
            $payMethods = GetPaymentMethods::callApi();
        } catch (PayUGeneralException $e) {
            return [];
        }
        return $payMethods->toArray();
    }
}
