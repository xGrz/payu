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
        LoggerService::error(self::buildLogMessage($response), self::getContext($response));
        throw new self(self::buildLogMessage($response), $response->json('status.code') ?? $response->status());
    }

    /**
     * @throws PayUGeneralException
     */
    public static function unAuthorized(Response $response)
    {
        LoggerService::error(self::buildLogMessage($response), self::getContext($response));
        throw new self(self::buildLogMessage($response), $response->json('status.code') ?? $response->status());
    }

    /**
     * @throws PayUGeneralException
     */
    public static function forbidden(Response $response)
    {
        LoggerService::error(self::buildLogMessage($response), self::getContext($response));
        throw new self(self::buildLogMessage($response), $response->json('status.code') ?? $response->status());
    }

    /**
     * @throws PayUGeneralException
     */
    public static function notFound(Response $response)
    {
        LoggerService::error(self::buildLogMessage($response), self::getContext($response));
        throw new self(self::buildLogMessage($response), $response->json('status.code') ?? $response->status());
    }

    public function getReason(): string
    {
        preg_match('/\((.*?)\)/', $this->getMessage(), $matches);
        return $matches[1] ?? 'Unknown';
    }

    private static function buildLogMessage(Response $response): string
    {
        $errorLiteral = match ($response->status()) {
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found'
        };

        $errorDescription = self::getErrorDescription($response);
        if (!empty($errorDescription)) {
            $errorDescription = '(' . $errorDescription . ')';
        }
        return '[HTTP: ' . $response->status() . "] $errorLiteral " . $errorDescription;
    }

    private static function getErrorDescription(Response $response): string
    {
        return $response->json('status.codeLiteral')
            ?? $response->json('status.statusDesc')
            ?? $response->json('status.error_description')
            ?? '';
    }

    private static function getContext(Response $response): array
    {
        return [
            'http_status' => $response->status(),
            'response' => $response->json()
        ];
    }
}
