<?php

namespace xGrz\PayU\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Exceptions\PayUResponseException;
use xGrz\PayU\Facades\Config;

class AuthService extends BaseApiCall
{
    protected static string $endpoint = '/pl/standard/user/oauth/authorize';

    /**
     * @throws PayUGeneralException
     */
    public static function getToken(): string
    {
        return cache()
            ->remember(
                Config::getCacheKey(),
                43199,
                fn() => self::createNewToken()
            );
    }

    public static function refreshToken(): string
    {
        self::invalidateToken();
        return self::getToken();
    }

    public static function invalidateToken(): void
    {
        cache()->forget(Config::getCacheKey());
    }

    /**
     * @throws PayUGeneralException
     */
    protected static function createNewToken(): string
    {
        $response = Http::acceptJson()
            ->asForm()
            ->post(self::getUri(), [
                'grant_type' => 'client_credentials',
                'client_id' => Config::getClientId(),
                'client_secret' => Config::getClientSecret(),
            ]);

        try {
            $response->throwIfStatus(400);
            $response->throwIfStatus(401);
        } catch (RequestException $e) {
            PayUResponseException::auth(
                $response->json('error'),
                $response->json('error_description'),
                $e->getCode()
            );
        }

        return $response->json('access_token');
    }

}
