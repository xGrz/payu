<?php

namespace xGrz\PayU\Facades;

use xGrz\PayU\Api\Actions\AcceptPayment;
use xGrz\PayU\Api\Actions\CancelOrder;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
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
}
