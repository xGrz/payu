<?php

namespace xGrz\PayU\Api\Exceptions;

class PayUPaymentException extends PayUGeneralException
{
    /**
     * @throws PayUPaymentException
     */
    public static function requiredAttributeNotSet(string $attribtueName): static
    {
        throw new static("Missing required value for [$attribtueName]", 404);
    }
}
