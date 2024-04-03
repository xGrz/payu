<?php

namespace xGrz\PayU\Api\Actions;


use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Responses\CreatePayoutResponse;
use xGrz\PayU\Facades\Config;

class CreatePayoutRequest extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/payouts';


    /**
     * Sends payment request for provided Payment asObject.
     */
    public static function callApi(int $amountInCents = null): CreatePayoutResponse
    {
        $payload = [
            'shopId' => Config::getShopId(),
            'payout' => [
                'amount' => $amountInCents
            ]
        ];

        return CreatePayoutResponse::consumeResponse(static::apiPostCall($payload))
            ->setAmount($amountInCents);
    }
}
