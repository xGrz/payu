<?php

namespace xGrz\PayU\Api\Actions;


use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\CancelPaymentResponse;
use xGrz\PayU\Models\Transaction;

class CancelOrder extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/orders/{payuOrderId}';


    /**
     * @throws PayUGeneralException
     */
    public static function callApi(Transaction $transaction): CancelPaymentResponse
    {
        self::defineEndpointParameter('payuOrderId', $transaction->payu_order_id);
        return CancelPaymentResponse::consumeResponse(self::apiDeleteCall());

    }

}
