<?php

namespace xGrz\PayU\Api;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Facades\Config;

abstract class BaseApiCall
{
    protected static string $endpoint = '';
    protected static array $endpoint_parameters = [];

    /**
     * @throws PayUGeneralException
     */
    protected static function getUri(): string
    {
        if (!static::$endpoint) throw new PayUGeneralException('Endpoint not defined');
        $endpoint = static::$endpoint;
        if (!empty(static::$endpoint_parameters)) {
            foreach (static::$endpoint_parameters as $parameter => $value) {
                if (empty($value)) throw new PayUGeneralException("Required endpoint parameter [$parameter] not defined.");
                $endpoint = str($endpoint)->replace('{' . $parameter . '}', $value);
            }
        }
        if (str($endpoint)->contains(['{', '}'])) {
            $missingParameters = str($endpoint)->matchAll('/\{(.*?)\}/')->join(', ', ' and ');
            throw new PayUGeneralException("Endpoint parameters not assigned: $missingParameters.");
        }

        return join('/', [
            str(Config::getServiceDomain())->rtrim('/'),
            str($endpoint)->ltrim('/')->rtrim('/')
        ]);
    }

    /**
     * @throws PayUGeneralException
     */
    private static function connection(): PendingRequest
    {
        return Http::acceptJson()
            ->contentType('application/json')
            ->withoutRedirecting()
            ->withToken(Config::getToken())
            ->withUserAgent('xGrz/PayU Laravel')
            ->timeout(1)
            ->connectTimeout(1)
            ->retry(2, 200, throw: false)
            ;
    }

    protected static function defineEndpointParameter(string $parameterName, string $value): void
    {
        static::$endpoint_parameters[$parameterName] = $value;
    }

    /**
     * @throws PayUGeneralException
     */
    protected static function apiGetCall(): ?object
    {
        try {
            return self::connection()->get(static::getUri());
        } catch (ConnectionException $e) {
            throw new PayUGeneralException('API connection problem');
        }

    }

    /**
     * @throws PayUGeneralException
     */
    protected static function apiPostCall(array $data = []): ?object
    {
        try {
            if (empty($data)) $data = null;
            return self::connection()->post(static::getUri(), $data);
        } catch (ConnectionException $e) {
            throw new PayUGeneralException('API connection problem');
        }
    }

    /**
     * @throws PayUGeneralException
     */
    protected static function apiDeleteCall(array $data = []): ?object
    {
        try {
            if (empty($data)) $data = null;
            return self::connection()->delete(static::getUri(), $data);
        } catch (ConnectionException $e) {
            throw new PayUGeneralException('API connection problem');
        }
    }
}
