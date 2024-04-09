<?php

require_once(__DIR__ . '/../Traits/WithTransaction.php');

use Tests\TestCase;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Services\ConfigService;

class ApiAuthTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        Config::set('payu.api.use_sandbox', true);
        Config::set('payu.api.oAuthClientId', ConfigService::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_ID']);
        Config::set('payu.api.oAuthClientSecret', ConfigService::SANDBOX_CREDENTIALS['PAYU_O_AUTH_CLIENT_SECRET']);
    }

    public function test_cache_key_builder_for_sandbox()
    {
        $sandboxCacheKey = (new ConfigService())->getCacheKey();
        $this->assertStringContainsString('payu:access_token_sandbox', $sandboxCacheKey);
    }

    public function test_cache_key_builder_for_production()
    {
        Config::set('payu.api.use_sandbox', false);
        $productionCacheKey = (new ConfigService())->getCacheKey();
        $this->assertStringContainsString('payu:access_token_production', $productionCacheKey);
    }

    public function test_get_api_authentication_token()
    {
        Cache::forget(\xGrz\PayU\Facades\Config::getCacheKey());
        $token1 = \xGrz\PayU\Facades\Config::getToken();

        Cache::forget(\xGrz\PayU\Facades\Config::getCacheKey());
        $token2 = \xGrz\PayU\Facades\Config::getToken();

        $this->assertNotEmpty($token1);
        $this->assertNotEmpty($token2);
        $this->assertNotEquals($token1, $token2);
    }

    public function test_failed_get_api_authentication_token()
    {
        Config::set('payu.api.oAuthClientId', 123456);
        Cache::forget(\xGrz\PayU\Facades\Config::getCacheKey());

        $this->expectException(PayUGeneralException::class);
        xGrz\PayU\Facades\Config::getToken();
    }

    public function test_is_api_token_cached()
    {
        Cache::forget(\xGrz\PayU\Facades\Config::getCacheKey());
        Http::fake([
            '*' => Http::response(['access_token' => 'abc']),
        ]);

        $token1 = \xGrz\PayU\Facades\Config::getToken();
        $token2 = \xGrz\PayU\Facades\Config::getToken();

        Http::assertSentCount(1);

        $this->assertEquals($token1, $token2);
    }
}


