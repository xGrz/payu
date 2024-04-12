<?php

require_once(__DIR__ . '/../Traits/WithTransactionWizard.php');

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
        $environment = app()->environment();
        app()->detectEnvironment(fn() => 'production');

        $productionCacheKey = (new ConfigService())->getCacheKey();
        $this->assertStringContainsString('payu:access_token_production', $productionCacheKey);

        app()->detectEnvironment(fn() => $environment); // back to original environment
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

    public function test_failed_get_api_authentication_token_success()
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
            '*' => Http::response([
                'access_token' => 'some-bearer-token',
                'token_type' => 'bearer',
                'expires_in' => 43199,
                'grant_type' => 'client_credentials',
            ]),
        ]);

        $token1 = \xGrz\PayU\Facades\Config::getToken();
        $token2 = \xGrz\PayU\Facades\Config::getToken();

        Http::assertSentCount(1);

        $this->assertEquals($token1, $token2);
        $this->assertEquals('some-bearer-token', $token1);
        Cache::forget(\xGrz\PayU\Facades\Config::getCacheKey());
    }

    public function test_get_api_token_400_throws_exception()
    {
        Cache::forget(\xGrz\PayU\Facades\Config::getCacheKey());
        Http::fake([
            '*' => Http::response([
                'error' => 'Bad request error',
                'error_description' => 'Description of error',
            ], 400),
        ]);
        $this->expectException(PayUGeneralException::class);
        $this->expectExceptionMessage('Bad request error: Description of error');

        \xGrz\PayU\Facades\Config::getToken();
    }

    public function test_get_api_token_401_throws_exception()
    {
        Cache::forget(\xGrz\PayU\Facades\Config::getCacheKey());
        Http::fake([
            '*' => Http::response([
                'error' => 'Unauthorized error',
                'error_description' => 'Description of error',
            ], 401),
        ]);
        $this->expectException(PayUGeneralException::class);
        $this->expectExceptionMessage('Unauthorized error');

        \xGrz\PayU\Facades\Config::getToken();
    }

}


