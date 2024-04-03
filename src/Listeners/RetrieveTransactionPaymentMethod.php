<?php

namespace xGrz\PayU\Listeners;

use xGrz\PayU\Api\Actions\RetrieveTransactionPayMethod;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Events\TransactionCompleted;
use xGrz\PayU\Services\LoggerService;

class RetrieveTransactionPaymentMethod
{
    public function __construct()
    {
        //
    }

    public function handle(TransactionCompleted $event): void
    {
        try {
            $payMethod = RetrieveTransactionPayMethod::callApi($event->transaction);

            $event->transaction->payMethod()->associate($payMethod->getMethod());
            $event->transaction->saveQuietly();

        } catch (PayUGeneralException $e) {
            LoggerService::error('Pay method retrieve failed');
        }

    }
}
