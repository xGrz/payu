<?php

namespace xGrz\PayU\Services;

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
        $keyType = config('payu.use_sandbox', true) ? 'sandbox' : 'production';
        $this->cache_key = 'payu:access_token_' . $keyType;
        return $this;
    }

    public function getClientId(): ?string
    {
        return config('payu.use_sandbox', true)
            ? config('payu.api.oAuthClientId', self::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_ID'])
            : config('payu.api.oAuthClientId');
    }

    public function getClientSecret(): string
    {
        return config('payu.use_sandbox', true)
            ? config('payu.api.oAuthClientSecret', self::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_SECRET'])
            : config('payu.api.oAuthClientSecret');
    }

    public function getSignatureKey(): string
    {
        return config('payu.use_sandbox', true)
            ? config('payu.api.signatureKey', self::SANDBOX_CREDENTIALS['PAYU_SIGNATURE_KEY'])
            : config('payu.api.signatureKey');
    }

    public function getShopId(): ?string
    {
        return config('payu.api.shopId');
    }

    public function getMerchantPosId(): int
    {
        return config('payu.use_sandbox', true)
            ? config('payu.api.merchantPosId', self::SANDBOX_CREDENTIALS['PAYU_MERCHANT_POS_ID'])
            : config('payu.api.merchantPosId');
    }

    public function getServiceDomain(): string
    {
        return config('payu.use_sandbox', true)
            ? 'https://secure.snd.payu.com'
            : 'https://secure.payu.com';
    }

    public function getCacheKey(): string
    {
        return $this->cache_key;
    }

    public function shouldBeLogged(): bool
    {
        return true;
    }
}
