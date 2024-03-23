<?php

namespace xGrz\PayU\Services;

use xGrz\PayU\Api\Exceptions\PayUResponseException;
use xGrz\PayU\Facades\Config;

class SignatureService
{

    /**
     * @throws PayUResponseException
     */
    public static function verify(): bool
    {
        $signatureData = self::parseIncomingSignature();
        $hash = self::buildHash($signatureData['algorithm']);
        return strcmp($hash, $signatureData['signature']) === 0;
    }

    /**
     * @throws PayUResponseException
     */
    private static function getSignature(): string|null
    {
        return request()->header('OpenPayu-Signature')
            ?? throw new PayUResponseException('PayU signature header not found', 400);
    }

    private static function buildHash(string $algorithm): string
    {
        $verificationKey = trim(request()->getContent()) . Config::getSignatureKey();
        return match ($algorithm) {
            'MD5' => md5($verificationKey),
            'SHA', 'SHA1', 'SHA-1' => sha1($verificationKey),
            default => hash('sha256', $verificationKey)
        };
    }

    /**
     * @throws PayUResponseException
     */
    private static function parseIncomingSignature(): array
    {
        $signatureRows = explode(';', self::getSignature());
        foreach ($signatureRows as $row) {
            $data = explode('=', $row);
            $signature[$data[0]] = $data[1];
        }

        if (empty($signature)) throw new PayUResponseException('PayU signature invalid format or missing data', 500);
        return [
            'algorithm' => $signature['algorithm'],
            'signature' => $signature['signature']
        ];
    }
}
