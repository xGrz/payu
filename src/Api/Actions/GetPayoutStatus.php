<?php

namespace xGrz\PayU\Api\Actions;


use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\GetPayoutStatusResponse;

class GetPayoutStatus extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/payouts/{payoutId}';


    /**
     * @param string $payoutId
     * @return GetPayoutStatusResponse
     * @throws PayUGeneralException
     */
    public static function callApi(string $payoutId): GetPayoutStatusResponse
    {
        self::defineEndpointParameter('payoutId', $payoutId);
        return GetPayoutStatusResponse::consumeResponse(static::apiGetCall());
    }
}
