<?php

namespace xGrz\PayU\Api\Actions;

use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\AcceptPaymentResponse;
use xGrz\PayU\Models\Transaction;

class AcceptPayment extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/orders/{payuOrderId}/captures';


    /**
     * @throws PayUGeneralException
     */
    public static function callApi(Transaction $transaction): AcceptPaymentResponse
    {
        self::defineEndpointParameter('payuOrderId', $transaction->payu_order_id);
        return AcceptPaymentResponse::consumeResponse(self::apiPostCall());
    }

}
