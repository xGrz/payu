<?php

namespace xGrz\PayU\Facades;

use xGrz\PayU\Actions\SyncPaymentMethods;
use xGrz\PayU\Api\Actions\AcceptPayment;
use xGrz\PayU\Api\Actions\CancelOrder;
use xGrz\PayU\Api\Actions\GetPaymentMethods;
use xGrz\PayU\Api\Actions\ShopBalance;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\ShopBalanceResponse;
use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Jobs\SendRefundJob;
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

    public static function cancelTransaction(Transaction $transaction): bool
    {
        if (!$transaction->status->hasAction('delete')) return false;
        return self::destroyTransaction($transaction);
    }

    public static function reject(Transaction $transaction): bool
    {
        if (!$transaction->status->hasAction('reject')) return false;
        return self::destroyTransaction($transaction);
    }

    public static function refund(Transaction $transaction, int|float $amount, string $description = null, string $backDescription = null, string $currencyCode = 'PLN'): bool
    {
        if (!$transaction->status->hasAction('refund')) return false;
        $transaction->refunds()->create([
            'amount' => $amount,
            'description' => $description,
            'bank_description' => $backDescription,
            'currency_code' => $currencyCode
        ]);
        return true;
    }

    public static function retryRefund(Refund $refund, int $delay = null): bool
    {
        SendRefundJob::dispatch($refund)
            ->delay(is_null($delay) ? Config::getRefundSendDelay() : 0);

        $refund
            ->update(['status' => RefundStatus::RETRY]);
        return true;

    }

    public static function cancelRefund(Refund $refund): bool
    {
        if (!$refund->status->hasAction('delete')) return false;
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
        dispatch(new UpdatePayoutStatusJob($payout))
            ->delay(is_null($delay) ? Config::getPayoutInterval() : $delay);

    }

    public static function cancelPayout(Payout $payout): bool
    {
        if (!$payout->status->hasAction('delete')) return false;

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

    public static function syncMethods(): bool
    {
        return SyncPaymentMethods::handle();
    }

    private static function destroyTransaction(Transaction $transaction): bool
    {
        try {
            $rejected = CancelOrder::callApi($transaction);
            return $rejected->isCanceled();
        } catch (PayUGeneralException $e) {
            return false;
        }
    }
}
