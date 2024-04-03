<?php

namespace xGrz\PayU\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use xGrz\PayU\Events\PayoutCreated;
use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Events\TransactionCompleted;
use xGrz\PayU\Listeners\DispatchPayoutRequest;
use xGrz\PayU\Listeners\DispatchRefundRequest;
use xGrz\PayU\Listeners\RetrieveTransactionPaymentMethod;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PayoutCreated::class => [
            DispatchPayoutRequest::class,
        ],
        RefundCreated::class => [
            DispatchRefundRequest::class
        ],
        TransactionCompleted::class => [
            RetrieveTransactionPaymentMethod::class
        ]
    ];


    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
