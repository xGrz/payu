<?php

namespace xGrz\PayU\Api\Actions;

use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\SendRefundRequestResponse;
use xGrz\PayU\Models\Refund;

class SendRequestRefund extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/orders/{orderId}/refunds';


    /**
     * @throws PayUGeneralException
     */
    public static function callApi(Refund $refund): SendRefundRequestResponse
    {
        $refund->loadMissing('transaction');
        $payload = [
            'refund' => [
                'description' => $refund->description,
                'amount' => (int)round($refund->amount * 100, 0),
                'extRefundId' => $refund->ext_refund_id,
                'currencyCode' => $refund->currency_code,
                'bankDescription' => $refund->bank_description
            ]
        ];
        self::defineEndpointParameter('orderId', $refund->transaction->payu_order_id);
        return SendRefundRequestResponse::consumeResponse(static::apiPostCall($payload));
    }
}
