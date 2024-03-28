<?php

namespace xGrz\PayU\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use xGrz\PayU\Events\PayoutCreated;
use xGrz\PayU\Listeners\DispatchPayoutRequest;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PayoutCreated::class => [
            DispatchPayoutRequest::class,
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
