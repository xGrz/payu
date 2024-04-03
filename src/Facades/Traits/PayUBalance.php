<?php

namespace xGrz\PayU\Facades\Traits;

use xGrz\PayU\Api\Actions\ShopBalance;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\ShopBalanceResponse;

trait PayUBalance
{
    public static function balance(): ?ShopBalanceResponse
    {
        return cache()->remember(
            'payu:balance',
            self::getBalanceCacheTtl(),
            fn() => self::getRealBalance()
        );
    }

    private static function getRealBalance(): ?ShopBalanceResponse
    {
        try {
            return ShopBalance::callApi();
        } catch (PayUGeneralException $e) {
            return null;
        }
    }

    private static function getBalanceCacheTtl(): int
    {
        $nextMinuteIn = now()->secondsUntil(now()->addMinute()->setSecond(0)->setMicro(0))->count();
        return $nextMinuteIn > 1
            ? $nextMinuteIn
            : 0;
    }

}
