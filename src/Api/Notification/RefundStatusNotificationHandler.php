<?php

namespace xGrz\PayU\Api\Notification;

use xGrz\PayU\Api\Exceptions\PayUResponseException;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Models\PayUTransaction;
use xGrz\PayU\Services\LoggerService;
use xGrz\PayU\Services\SignatureService;

class RefundStatusNotificationHandler
{
    private PaymentStatus $prevStatus;
    private PaymentStatus $nextStatus;

    private bool $wasUpdated = false;

    /**
     * @throws PayUResponseException
     */
    private function __construct(private readonly PayUTransaction $transaction, array $refundData)
    {
        if (!SignatureService::verify()) throw new PayUResponseException('Invalid PayU signature', 401);

        $refundModel = [
            'refund_id' => $refundData['refund']['refundId'],
            'ext_refund_id' => $refundData['refund']['extRefundId'],
            'description' => $refundData['refund']['reasonDescription'],
            'amount' => $refundData['refund']['amount'] / 100,
            'status' => RefundStatus::findByName($refundData['refund']['status']),
            'currency_code' => $refundData['refund']['currencyCode'],
        ];

        LoggerService::info($this->transaction->refunds()->count());
        $this->transaction->refunds()->updateOrCreate(['ext_refund_id' => $refundModel['ext_refund_id']], $refundModel);
        LoggerService::info($this->transaction->refunds()->count());
    }

    /**
     * @throws PayUResponseException
     */
    public static function consumeNotification(PayUTransaction $transaction, array $refundData): RefundStatusNotificationHandler
    {
        return new RefundStatusNotificationHandler($transaction, $refundData);
    }
}
