<?php

namespace xGrz\PayU;

use Illuminate\Support\ServiceProvider;
use xGrz\PayU\Services\ConfigService;

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

        $this->app->singleton(ConfigService::class, fn() => new ConfigService());

        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('payu.php', '')
        ], 'payu');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
