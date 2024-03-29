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
        $message = '[HTTP: '. $response->status() . '] PayU Bad request';
        if ($response->json('status.codeLiteral')) $message .= '(' . $response->json('status.codeLiteral') .')' ;
        LoggerService::error($message, ['http_status' => $response->status(), 'response' => $response->json()]);
        throw new self($message, $response->json('status.code'));
    }

    /**
     * @throws PayUGeneralException
     */
    public static function unAuthorized(Response $response)
    {
        $message = '[HTTP: '. $response->status() . '] PayU unauthorized' ;
        LoggerService::error($message, ['http_status' => $response->status()]);
        throw new self($message);
    }

    /**
     * @throws PayUGeneralException
     */
    public static function forbidden(Response $response)
    {
        $message = '[HTTP: '. $response->status() . '] PayU forbidden' ;
        LoggerService::error($message, ['http_status' => $response->status()]);
        throw new self($message);
    }

    /**
     * @throws PayUGeneralException
     */
    public static function notFound(Response $response)
    {
        $message = '[HTTP: '. $response->status() . '] PayU not found' ;
        LoggerService::error($message, ['http_status' => $response->status()]);
        throw new self($message);
    }

    public function getReason(): string
    {
        preg_match('/\((.*?)\)/', $this->getMessage(), $matches);
        return $matches[1] ?? 'Unknown';
    }
}
