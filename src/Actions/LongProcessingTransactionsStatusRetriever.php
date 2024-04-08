<?php

namespace xGrz\PayU\Actions;

use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Services\LoggerService;

class LongProcessingTransactionsStatusRetriever
{
    public static function handle(): void
    {
        $processing = Transaction::whereIn('status', PaymentStatus::withAction('processing'))
            ->where('updated_at', '>', now()->subDays(2))
            ->where('updated_at', '<', now()->subHour())
            ->get();

        if ($processing->count() > 0) {
            LoggerService::warning('Long running transactions found (' .  $processing->count() . ')');
            $processing->each(function (Transaction $transaction) {
                PayU::forceUpdatePaymentStatus($transaction);
            });
        }

    }
}
