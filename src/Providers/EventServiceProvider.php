<?php

namespace xGrz\PayU\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use xGrz\PayU\Events\PayoutCreated;
use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Listeners\DispatchPayoutRequest;
use xGrz\PayU\Listeners\DispatchRefundRequest;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PayoutCreated::class => [
            DispatchPayoutRequest::class,
        ],
        RefundCreated::class => [
            DispatchRefundRequest::class
        ],
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
