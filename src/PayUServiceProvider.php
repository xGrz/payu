<?php

namespace xGrz\PayU;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use xGrz\PayU\Services\ConfigService;

class PayUServiceProvider extends ServiceProvider
{

    public function register(): void
    {
    }

    public function boot(): void
    {
        self::setupMigrations();
        self::setupPackageConfig();
        self::setupNotificationRouting();
        self::setupWebRouting();

        $this->app->booted(function () {
            $schedule = app(Schedule::class);
            self::setupScheduler($schedule);
        });
    }

    private function setupMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    private function setupPackageConfig(): void
    {
        $this->app->singleton(ConfigService::class, fn() => new ConfigService());

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('payu.php', '')
            ], 'payu');
        }

    }

    private function setupWebRouting(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payu');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function setupNotificationRouting(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    private function setupScheduler(Schedule $schedule): void
    {
//        $schedule
//            ->call(fn() => UpdatePayoutsStates::handle())
//            ->everyFiveMinutes()
//            ->name('PayU | Update payouts states');
//
//        $schedule
//            ->call(fn() => (new ProcessFillTransactionsPayMethodJob())->handle())
//            ->name('PayU | Fill payment method for transactions')
//            ->everyFiveMinutes();
//
//        $schedule
//            ->call(fn() => SyncPaymentMethods::handle())
//            ->name('PayU | Synchronize payment methods')
//            ->weekdays()
//            ->at('4:30');
//
//        $schedule
//            ->call(fn() => ProcessRefundsToTransactions::handle())
//            ->name('PayU | Process refunds')
//            ->everyThirtyMinutes();
//
    }
}
