<?php

namespace xGrz\PayU\Services;

use xGrz\PayU\Models\Method;

class ConfigService
{
    const SANDBOX_CREDENTIALS = [
        'PAYU_SHOP_ID' => '',
        'PAYU_MERCHANT_POS_ID' => 300746,
        'PAYU_SIGNATURE_KEY' => 'b6ca15b0d1020e8094d9b5f8d163db54',
        'PAYU_O_AUTH_CLIENT_ID' => 300746,
        'PAYU_O_AUTH_CLIENT_SECRET' => '2ee86a66e5d97e3fadc400c9f19b065d',
    ];

    protected string $cache_key;

    public function __construct()
    {
        self::buildCacheKey();
    }


    private function buildCacheKey(): static
    {
        $envType = self::isSandboxMode() ? 'sandbox' : 'production';

        $config = join(':', [
            self::getServiceDomain(),
            self::getMerchantPosId(),
            self::getShopId(),
            self::getSignatureKey(),
            self::getClientSecret(),
            self::getClientId()
        ]);
        $this->cache_key = 'payu:access_token_' . $envType . ':' . md5($config);
        return $this;
    }

    public function getClientId(): ?int
    {
        if (self::isSandboxMode()) {
            return config('payu.api.oAuthClientId') ?? self::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_ID'];
        }
        return config('payu.api.oAuthClientId');
    }

    public function getClientSecret(): ?string
    {
        if (self::isSandboxMode()) {
            return config('payu.api.oAuthClientSecret') ?? self::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_SECRET'];
        }
        return config('payu.api.oAuthClientSecret');
    }

    public function getSignatureKey(): ?string
    {
        if (self::isSandboxMode()) {
            return config('payu.api.signatureKey') ?? self::SANDBOX_CREDENTIALS['PAYU_SIGNATURE_KEY'];
        }
        return config('payu.api.signatureKey');
    }

    public function getShopId(): ?string
    {
        return config('payu.api.shopId');
    }

    public function getMerchantPosId(): ?int
    {
        if (self::isSandboxMode()) {
            return config('payu.api.merchantPosId') ?? self::SANDBOX_CREDENTIALS['PAYU_MERCHANT_POS_ID'];
        }
        return config('payu.api.merchantPosId');
    }

    public function getServiceDomain(): string
    {
        return self::isSandboxMode()
            ? 'https://secure.snd.payu.com'
            : 'https://secure.payu.com';
    }

    public function getCacheKey(): string
    {
        return $this->cache_key;
    }

    public function getBalanceCacheKey(): string
    {
        return 'payu:balance';
    }

    public function shouldBeLogged(): bool
    {
        return true;
    }

    public function getPayoutInterval(): int
    {
        return config('payu.jobs.interval.payout_status_check', 120);
    }

    public function getPayoutSendDelay(): int
    {
        return config('payu.jobs.delay.payout.send', 120);
    }

    public function getPayoutRetryDelay(): int
    {
        return config('payu.jobs.delay.payout.retry', 120);
    }

    public function getRefundSendDelay(): int
    {
        return config('payu.jobs.delay.refund.send', 120);
    }

    public function getRefundRetryDelay(): int
    {
        return config('payu.jobs.delay.refund.retry', 120);
    }

    public function getTransactionMethodCheckDelay(): int
    {
        return config('payu.jobs.delay.transaction_method_check', 120);
    }

    public function hasPayMethods(): bool
    {
        return (bool)Method::count();
    }

    public function getPaymentController(): string
    {
        return config(
            'payu.expose_admin_panel.paymentController',
            \xGrz\PayU\Http\Controllers\PaymentController::class
        );
    }

    public function getPayoutController(): string
    {
        return config(
            'payu.expose_admin_panel.payoutController',
            \xGrz\PayU\Http\Controllers\PayoutController::class
        );
    }

    public function getRefundController(): string
    {
        return config(
            'payu.expose_admin_panel.refundController',
            \xGrz\PayU\Http\Controllers\RefundController::class
        );
    }

    public function getMethodsController()
    {
        return config(
            'payu.routing.controllers.methods',
            \xGrz\PayU\Http\Controllers\MethodsController::class
        );
    }

    public function getRouteName(string $routeExtension = null): string
    {
        $rootName = str(config('payu.routing.web.group_name', 'payu'))
            ->whenStartsWith('.', fn($rootName) => str($rootName)->replaceFirst('.', ''))
            ->whenEndsWith('.', fn($rootName) => str($rootName)->replaceLast('.', ''));

        $routeExtension = str($routeExtension)
            ->whenStartsWith('.', fn($routeExt) => str($routeExt)->replaceFirst('.', ''))
            ->whenEndsWith('.', fn($routeExt) => str($routeExt)->replaceLast('.', ''));

        return join('.', [$rootName, $routeExtension]);
    }

    public function getUri(string $suffix): string
    {
        return join('/', [
            config('payu.routing.web.uri_prefix', 'payu'),
            $suffix
        ]);
    }

    public function isSandboxMode(): bool
    {
        return !app()->environment('production');
    }

}
