<?php

namespace xGrz\PayU\Api\Actions;


use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\GetPayoutStatusResponse;
use xGrz\PayU\Api\Responses\GetTransactionStatusResponse;
use xGrz\PayU\Models\Transaction;

class GetTransactionStatus extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/orders/{orderId}';


    /**
     * @param string $payoutId
     * @return GetPayoutStatusResponse
     * @throws PayUGeneralException
     */
    public static function callApi(Transaction $transaction): GetTransactionStatusResponse
    {
        self::defineEndpointParameter('orderId', $transaction->payu_order_id);
        return GetTransactionStatusResponse::consumeResponse(static::apiGetCall());
    }
}
