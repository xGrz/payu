<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{


    public function __invoke()
    {

    }
//    public function __invoke(NotificationRequest $request, PayUTransaction $transaction): Response
//    {
//        if ($request->validated('order')) {
//            return self::orderChanged($transaction, $request->validated('order'));
//        }
//
//        if ($request->validated('refund')) {
//            return self::refundChange($transaction, $request->validated());
//        }
//
//        return response('Invalid notification type', 500);
//
//    }
//
//    private function orderChanged(PayUTransaction $transaction, array $orderData): Response
//    {
//        try {
//            $transactionStatus = HandlePayUStatusChangeNotificationAction::consumeNotification(
//                $transaction,
//                $orderData
//            );
//        } catch (PayUResponseException $e) {
//            return response($e->getMessage(), $e->getCode() ?? 500);
//        }
//        return $transactionStatus->updated()
//            ? response('New status: ' . $transactionStatus->currentStatus()->name, 200)
//            : response('Status not changed', 200);
//
//    }
//
//    private function refundChange(PayUTransaction $transaction, array $refundData): Response
//    {
//        try {
//            $refund = HandlePayURefundChangeNotificationAction::consumeNotification(
//                $transaction,
//                $refundData
//            );
//        } catch (PayUResponseException $e) {
//            return response($e->getMessage(), $e->getCode() ?? 500);
//        }
//
//        return \response('ok', 200);
////        return $refund->updated()
////            ? response('New status: ' . $refund->currentStatus()->name, 200)
////            : response('Status not changed', 200);
//    }
//
}

