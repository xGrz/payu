<?php

namespace xGrz\PayU\Facades\Traits;

use xGrz\PayU\Api\Actions\AcceptPayment;
use xGrz\PayU\Api\Actions\CancelOrder;
use xGrz\PayU\Api\Actions\CreatePaymentAction;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Models\Transaction;

trait PayUTransaction
{
    public static function getTransactionWizard(): TransactionWizard
    {
        return new TransactionWizard();
    }

    public static function createPayment(TransactionWizard $transaction)
    {
        return CreatePaymentAction::callApi($transaction);
    }

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
        if (!$transaction->status->hasAction('reject')) return false;
        return self::destroyTransaction($transaction);
    }

    public static function cancelTransaction(Transaction $transaction): bool
    {
        if (!$transaction->status->hasAction('delete')) return false;
        return self::destroyTransaction($transaction);
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
