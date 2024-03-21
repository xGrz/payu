<?php

namespace xGrz\PayU;

use Illuminate\Support\ServiceProvider;

class PayUServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

    }
}
