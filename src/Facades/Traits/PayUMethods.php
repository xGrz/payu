<?php

namespace xGrz\PayU\Facades\Traits;

use xGrz\PayU\Actions\SyncPaymentMethods;
use xGrz\PayU\Api\Actions\GetPaymentMethods;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;

trait PayUMethods
{
    public static function getMethods(): array
    {
        try {
            $payMethods = GetPaymentMethods::callApi();
        } catch (PayUGeneralException $e) {
            return [];
        }
        return $payMethods->toArray();
    }

    public static function syncMethods(): bool
    {
        return SyncPaymentMethods::handle();
    }

}
