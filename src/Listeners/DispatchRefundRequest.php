<?php

namespace xGrz\PayU\Listeners;

use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Jobs\SendRefundJob;
use xGrz\PayU\Models\Refund;

class DispatchRefundRequest
{
    public function handle(RefundCreated $refundCreatedEvent): void
    {
        if (is_null($refundCreatedEvent->refund->status) || $refundCreatedEvent->refund->status->hasAction('send')) {
            self::updateStatus($refundCreatedEvent->refund);
            self::dispatchJob($refundCreatedEvent->refund);
        }
    }

    private function updateStatus(Refund $refund): void
    {
        $refund->update(['status' => RefundStatus::SCHEDULED]);

    }

    private function dispatchJob(Refund $refund): void
    {
        SendRefundJob::dispatch($refund)
            ->delay(Config::getRefundSendDelay());

    }
}
