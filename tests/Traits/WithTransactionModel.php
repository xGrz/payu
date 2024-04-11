<?php

namespace Traits;

use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Models\Transaction;

trait WithTransactionModel
{
    private function createTransaction(PaymentStatus $paymentStatus = PaymentStatus::INITIALIZED, bool $createEventSilenced = true): Transaction
    {
        $transaction = new Transaction([
            'payu_order_id' => 'AIDJAODJAODJAOIJD',
            'link' => 'https://payu.com',
            'payload' => [],
            'status' => $paymentStatus
        ]);
        $transaction
            ->when(
                $createEventSilenced,
                fn() => $transaction->saveQuietly(),
                fn() => $transaction->save()
            );
        return $transaction;
    }

    private function addRefund(Transaction $transaction, RefundStatus $refundStatus = RefundStatus::INITIALIZED, string $error = ''): Transaction
    {
        $transaction->refunds()->create([
            'status' => $refundStatus,
            'description' => 'RMA',
            'bank_description' => 'RMA',
            'amount' => 1000,
            'currency_code' => 'PLN',
            'refund_id' => 'AOADODIAODIAODIA',
            'ext_order_id' => 'AOADSOPDKSPDK',
            'error' => $refundStatus === RefundStatus::ERROR ? $error : null,
        ]);
        return $transaction;
    }
}
