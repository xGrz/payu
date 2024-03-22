<?php

namespace xGrz\PayU\Api\Exceptions;

use Illuminate\Http\Client\Response;
use xGrz\PayU\Services\LoggerService;

class PayUResponseException extends PayUGeneralException
{

    /**
     * @throws PayUGeneralException
     */
    public static function auth($error, $description = '', $code = 0): PayUResponseException
    {
        $message = "PayU Auth | $error: $description";
        LoggerService::warning($message, [
            'error' => $error,
            'description' => $description
        ]);
        throw new self("$error: $description", $code);
    }

    /**
     * @throws PayUGeneralException
     */
    public static function bedRequest(Response $response)
    {
        $message = '[HTTP: '. $response->status() . '] PayU bad request' ;
        LoggerService::warning($message, ['http_status' => $response->status()]);
        throw new self($message);
    }

    /**
     * @throws PayUGeneralException
     */
    public static function unAuthorized(Response $response)
    {
        $message = '[HTTP: '. $response->status() . '] PayU unauthorized' ;
        LoggerService::warning($message, ['http_status' => $response->status()]);
        throw new self($message);
    }

    /**
     * @throws PayUGeneralException
     */
    public static function forbidden(Response $response)
    {
        $message = '[HTTP: '. $response->status() . '] PayU forbidden' ;
        LoggerService::warning($message, ['http_status' => $response->status()]);
        throw new self($message);
    }

    /**
     * @throws PayUGeneralException
     */
    public static function notFound(Response $response)
    {
        $message = '[HTTP: '. $response->status() . '] PayU not found' ;
        LoggerService::warning($message, ['http_status' => $response->status()]);
        throw new self($message);
    }
}
