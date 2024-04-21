<?php

namespace xGrz\PayU;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use xGrz\PayU\Actions\LongProcessingTransactionsStatusRetriever;
use xGrz\PayU\Actions\SyncPaymentMethods;
use xGrz\PayU\Commands\PayMethodsUpdateCommand;
use xGrz\PayU\Commands\PublishCommand;
use xGrz\PayU\Commands\PublishConfigCommand;
use xGrz\PayU\Commands\PublishLangCommand;
use xGrz\PayU\Models\Payout;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Observers\PayoutObserver;
use xGrz\PayU\Observers\RefundObserver;
use xGrz\PayU\Observers\TransactionObserver;
use xGrz\PayU\Providers\EventServiceProvider;
use xGrz\PayU\Services\ConfigService;

class PayUServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        self::setupMigrations();
        self::setupPackageConfig();
        self::setupNotificationRouting();
        self::setupWebRouting();
        self::setupCommands();
        self::setupTranslations();

        Payout::observe(PayoutObserver::class);
        Refund::observe(RefundObserver::class);
        Transaction::observe(TransactionObserver::class);

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
                __DIR__ . '/../config/config.php' => config_path('payu.php'),
            ], 'payu-config');
            $this->publishes([
                __DIR__ . '/../lang' => $this->app->langPath('vendor/payu')
            ], 'payu-lang');
        }

    }

    private function setupWebRouting(): void
    {
        if (!config('payu.routing.expose_web_panel', false)) return;

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payu');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        View::share('qbp_appName', 'xGrz/PayU');
        View::share('qbp_useTailwind', true);
        View::share('qbp_useAlpine', true);
        View::share('qbp_navigationTemplate', 'p::navigation.container');
        View::share('qbp_navigationItems', '');
        View::share('qbp_footerTemplate', 'p::footer.content');
    }

    private function setupNotificationRouting(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    private function setupCommands(): void
    {
        $this->commands([
            PublishCommand::class,
            PublishLangCommand::class,
            PublishConfigCommand::class,
            PayMethodsUpdateCommand::class,
        ]);
    }

    private function setupScheduler(Schedule $schedule): void
    {
        $schedule
            ->call(fn() => SyncPaymentMethods::handle())
            ->name('PayU | Synchronize payment methods')
            ->weekdays()
            ->at('4:30');

        $schedule
            ->call(fn() => LongProcessingTransactionsStatusRetriever::handle())
            ->name('PayU | Retrieve transaction states for unfinished payments')
            ->everyMinute();
        // ->everyTwoHours();
    }

    private function setupTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'payu');
    }
}
