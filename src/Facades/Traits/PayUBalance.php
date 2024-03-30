<?php

namespace xGrz\PayU\Facades\Traits;

use xGrz\PayU\Api\Actions\ShopBalance;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\ShopBalanceResponse;

trait PayUBalance
{
    public static function balance(): ?ShopBalanceResponse
    {
        try {
            return ShopBalance::callApi();
        } catch (PayUGeneralException $e) {
            return null;
        }
    }
}
