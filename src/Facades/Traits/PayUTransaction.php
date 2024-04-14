<?php

namespace xGrz\PayU\Facades\Traits;

use xGrz\PayU\Api\Actions\AcceptPayment;
use xGrz\PayU\Api\Actions\CancelOrder;
use xGrz\PayU\Api\Actions\CreatePaymentAction;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Jobs\RetrieveTransactionStatusJob;
use xGrz\PayU\Models\Transaction;

trait PayUTransaction
{
    public static function getTransactionWizard(): TransactionWizard
    {
        return new TransactionWizard();
    }

    public static function forceUpdatePaymentStatus(Transaction $transaction, int $delay = null): void
    {
        if (!$transaction->status->hasAction('processing')) return;
        RetrieveTransactionStatusJob::dispatch($transaction)
            ->delay($delay ?? 0);
        ;
    }

    /**
     * @throws PayUGeneralException
     */
    public static function createPayment(TransactionWizard $transaction): ?Transaction
    {
        return CreatePaymentAction::callApi($transaction);
    }

    public static function accept(Transaction $transaction): bool
    {
        if (!$transaction->status->hasAction('accept')) return false;

        try {
            $accept = AcceptPayment::callApi($transaction);
            return $accept->isAccepted();
        } catch (PayUGeneralException $e) {
            return false;
        }
    }

    public static function reject(Transaction $transaction): bool
    {
        if (!$transaction->status->hasAction('reject')) return false;

        try {
            $rejected = CancelOrder::callApi($transaction);
            return $rejected->isCanceled();
        } catch (PayUGeneralException $e) {
            return false;
        }
    }

    public static function cancelTransaction(Transaction $transaction): bool
    {
        if (!$transaction->status->hasAction('delete')) return false;
        try {
            $rejected = CancelOrder::callApi($transaction);
            return $rejected->isCanceled();
        } catch (PayUGeneralException $e) {
            return false;
        }
    }

    public static function resetTransaction(Transaction $transaction): bool
    {
        if (!$transaction->status->hasAction('reset')) return false;
        try {
            $rejected = CancelOrder::callApi($transaction);
            return $rejected->isCanceled();
        } catch (PayUGeneralException $e) {
            return false;
        }
    }

}
