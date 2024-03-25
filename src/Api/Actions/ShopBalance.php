<?php

namespace xGrz\PayU\Api\Actions;

use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\ShopBalanceResponse;
use xGrz\PayU\Facades\Config;

class ShopBalance extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/shops/{shopId}';


    /**
     * @throws PayUGeneralException
     */
    public static function callApi(?string $shopId = null): ShopBalanceResponse
    {
        $shopId = $shopId ?? Config::getShopId();
        if (!$shopId) throw new PayUGeneralException('ShopId not defined');

        self::defineEndpointParameter('shopId', $shopId);
        $shopBalance = static::apiGetCall();

        return ShopBalanceResponse::consumeResponse($shopBalance);
    }
}
