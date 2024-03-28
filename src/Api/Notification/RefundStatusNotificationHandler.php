<?php

namespace xGrz\PayU\Api\Notification;

use xGrz\PayU\Api\Exceptions\PayUResponseException;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Services\SignatureService;

class RefundStatusNotificationHandler
{
    private PaymentStatus $prevStatus;
    private PaymentStatus $nextStatus;

    private bool $wasUpdated = false;

    /**
     * @throws PayUResponseException
     */
    private function __construct(private readonly Transaction $transaction, array $refundData)
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

        $this->transaction->refunds()->updateOrCreate(['refund_id' => $refundModel['refund_id']], $refundModel);
    }

    /**
     * @throws PayUResponseException
     */
    public static function consumeNotification(Transaction $transaction, array $refundData): RefundStatusNotificationHandler
    {
        return new RefundStatusNotificationHandler($transaction, $refundData);
    }
}
