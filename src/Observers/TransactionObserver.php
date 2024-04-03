<?php

namespace xGrz\PayU\Observers;


use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Events\PendingTransaction;
use xGrz\PayU\Events\TransactionCanceled;
use xGrz\PayU\Events\TransactionCompleted;
use xGrz\PayU\Events\TransactionCreated;
use xGrz\PayU\Events\TransactionPaid;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Traits\WithStatusChangeObserver;

class TransactionObserver
{
    use WithStatusChangeObserver;

    public function created(Transaction $transaction): void
    {
        TransactionCreated::dispatch($transaction);
    }

    public function updating(Transaction $transaction): void
    {
        self::whenStatusChangedTo($transaction,PaymentStatus::PENDING, PendingTransaction::class);
        self::whenStatusChangedTo($transaction,PaymentStatus::WAITING_FOR_CONFIRMATION, TransactionPaid::class);
        self::whenStatusChangedTo($transaction,PaymentStatus::COMPLETED, TransactionCompleted::class);
        self::whenStatusChangedTo($transaction,PaymentStatus::CANCELED, TransactionCanceled::class);
    }



}
