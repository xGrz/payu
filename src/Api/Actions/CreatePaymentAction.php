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
    public static function callApi(TransactionWizard $payment)
    {
        $transactionWizardData = $payment->toArray();

        $result = static::apiPostCall($transactionWizardData);
        $response = CreatePaymentResult::consumeResponse($result)->toArray();

        $transaction = new Transaction();
        $transaction->fill([
            'id' => $transactionWizardData['extOrderId'],
            'amount' => $transaction['totalAmount'],
            'payload' => $transactionWizardData,
        ]);
        $transaction->fill($response);
        $transaction->save();
    }
}
