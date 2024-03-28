<?php

namespace xGrz\PayU\Api\Actions;


use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\GetPaymentMethodsResponse;

/**
 * Retrieve All Available Payment Methods.
 */
class GetPaymentMethods extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/paymethods';


    /**
     * Retrieves Payment methods from api
     *
     * @throws PayUGeneralException
     */
    public static function callApi(): GetPaymentMethodsResponse
    {
        $paymentMethods = static::apiGetCall();
        return GetPaymentMethodsResponse::consumeResponse($paymentMethods);
    }

}
