<?php

namespace xGrz\PayU\Api\Actions;

use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\RetrieveTransactionPayMethodResponse;
use xGrz\PayU\Models\Transaction;

class RetrieveTransactionPayMethod extends BaseApiCall
{
    protected static string $endpoint = '/api/v2_1/orders/{payuOrderId}/transactions';
    protected static array $endpoint_parameters = [
        'payuOrderId' => null
    ];


    /**
     * @throws PayUGeneralException
     */
    public static function callApi(Transaction $transaction): RetrieveTransactionPayMethodResponse
    {
        self::defineEndpointParameter('payuOrderId', $transaction->payu_order_id);
        return RetrieveTransactionPayMethodResponse::consumeResponse(self::apiGetCall());
    }
}
