<?php

namespace xGrz\PayU\Facades\Traits;

use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Jobs\SendRefundJob;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Models\Transaction;

trait PayURefunds
{
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
        // todo: add protection
        SendRefundJob::dispatch($refund)
            ->delay(is_null($delay) ? Config::getRefundRetryDelay() : $delay);

        $refund
            ->update(['status' => RefundStatus::RETRY]);
        return true;

    }

    public static function cancelRefund(Refund $refund): bool
    {
        if (!$refund->status->hasAction('delete')) return false;
        return (bool)$refund->delete();
    }

}
