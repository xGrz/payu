<?php

namespace xGrz\PayU\Api;

use Illuminate\Http\Client\Response;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Exceptions\PayUResponseException;

abstract class BaseApiResponse
{
    protected array $data = [];

    /**
     * @throws PayUGeneralException
     */
    final public static function consumeResponse(Response $response): static
    {
        self::errorHandler($response);
        return new static($response);
    }


    public function toArray(): array
    {
        return $this->data;
    }

    public function asObject(): object
    {
        return json_decode(json_encode($this->data));
    }

    public function __get($key): mixed
    {
        return $this->asObject()->$key;
    }

    /**
     * @throws PayUGeneralException
     * @throws PayUResponseException
     */
    protected static function errorHandler(Response $response): void
    {
        match ($response->status()) {
            200, 201, 204, 302 => null, // expected success status codes allow passing by
            400 => PayUResponseException::bedRequest($response),
            401 => PayUResponseException::unAuthorized($response),
            403 => PayUResponseException::forbidden($response),
            404 => PayUResponseException::notFound($response),
            500 => throw new PayUResponseException('PayU Service error'),
            503 => throw new PayUResponseException('PayU Service unavailable'),
            default => throw new PayUResponseException('Unexpected HTTP code response ' . $response->status())
        };
    }
}
