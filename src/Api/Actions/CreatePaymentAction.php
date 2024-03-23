<?php

namespace xGrz\PayU\Api\Actions;

use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Responses\CreatePaymentResult;
use xGrz\PayU\Facades\TransactionWizard;

class CreatePaymentAction extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/orders';


    /**
     * Sends payment request for provided Payment asObject. If created returns PayUTransaction model from local DB
     */
    public static function callApi(TransactionWizard $payment)
    {
        $payu_payment = static::apiPostCall($payment->toArray());
        return CreatePaymentResult::consumeResponse($payu_payment)
            ->setPayload($payment->toArray())
            ->getTransaction();
    }
}
