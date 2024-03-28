<?php

namespace xGrz\PayU\Api\Actions;

use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\SendPayoutRequestResponse;
use xGrz\PayU\Facades\Config;

class SendPayoutRequest extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/payouts';


    /**
     * Sends payment request for provided Payment asObject.
     * @throws PayUGeneralException
     */
    public static function callApi(int $amountInCents = null): SendPayoutRequestResponse
    {
        $payload = [
            'shopId' => Config::getShopId(),
            'payout' => [
                'amount' => $amountInCents
            ]
        ];

        return SendPayoutRequestResponse::consumeResponse(static::apiPostCall($payload))
            ->setAmount($amountInCents);
    }
}
