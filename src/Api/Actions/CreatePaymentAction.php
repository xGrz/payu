<?php

namespace xGrz\PayU\Api\Actions;

use xGrz\PayU\Api\BaseApiCall;
use xGrz\PayU\Api\Responses\CreatePaymentResult;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Models\Transaction;

class CreatePaymentAction extends BaseApiCall
{
    protected static string $endpoint = 'api/v2_1/orders';


    /**
     * Sends payment request for provided Payment asObject. If created returns PayUTransaction model from local DB
     */
    public static function callApi(TransactionWizard $payment): Transaction
    {
        $transactionWizardData = $payment->toArray();

        $result = static::apiPostCall($transactionWizardData);
        $response = CreatePaymentResult::consumeResponse($result)->toArray();

        $transaction = new Transaction();
        $transaction->fill([
            'id' => $transactionWizardData['extOrderId'],
            'amount' => $transactionWizardData['totalAmount'] / 100,
            'payload' => $transactionWizardData,
        ]);
        if (isset($transactionWizardData['payMethods']['payMethod']['value'])) {
            $transaction->method_id = $transactionWizardData['payMethods']['payMethod']['value'];
        }
        $transaction->fill($response);
        $transaction->save();
        return $transaction;
    }
}
