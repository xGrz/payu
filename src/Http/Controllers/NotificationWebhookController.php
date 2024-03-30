<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use xGrz\PayU\Api\Exceptions\PayUResponseException;
use xGrz\PayU\Api\Notification\RefundStatusNotificationHandler;
use xGrz\PayU\Api\Notification\TransactionStatusNotificationHandler;
use xGrz\PayU\Http\Requests\NotificationRequest;
use xGrz\PayU\Models\Transaction;

class NotificationWebhookController extends Controller
{


    public function __invoke(NotificationRequest $request, Transaction $transaction)
    {

        if ($request->validated('order')) {
            return self::orderChanged($transaction, $request->validated('order'));
        }

        if ($request->validated('refund')) {
            return self::refundChange($transaction, $request->validated());
        }

        return response('Invalid notification type', 500);
    }

    private function orderChanged(Transaction $transaction, array $orderData): Response
    {
        try {
            $transactionStatus = TransactionStatusNotificationHandler::consumeNotification(
                $transaction,
                $orderData
            );
        } catch (PayUResponseException $e) {
            return response($e->getMessage(), $e->getCode() ?? 500);
        }
        return $transactionStatus->updated()
            ? response('New status: ' . $transactionStatus->currentStatus()->name, 200)
            : response('Status not changed', 200);

    }

    private function refundChange(Transaction $transaction, array $refundData): Response
    {
        try {
            $refund = RefundStatusNotificationHandler::consumeNotification(
                $transaction,
                $refundData
            );
        } catch (PayUResponseException $e) {
            return response($e->getMessage(), $e->getCode() ?? 500);
        }

        return response('ok', 200);
    }

}

