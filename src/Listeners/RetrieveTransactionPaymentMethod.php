<?php

namespace xGrz\PayU\Listeners;

use xGrz\PayU\Events\TransactionCompleted;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Jobs\RetrieveTransactionPayMethodJob;

class RetrieveTransactionPaymentMethod
{
    public function __construct()
    {
        //
    }

    public function handle(TransactionCompleted $event): void
    {
        RetrieveTransactionPayMethodJob::dispatch($event->transaction)
            ->delay(Config::getTransactionMethodCheckDelay());
    }
}
