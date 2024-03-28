<?php

namespace xGrz\PayU\Listeners;

use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Jobs\SendRefundJob;
use xGrz\PayU\Models\Refund;

class DispatchRefundRequest
{
    public function handle(RefundCreated $event): void
    {
        if (is_null($event->refund->status) || $event->refund->status->actionAvailable('send')) {
            self::updateStatus($event->refund);
            self::dispatchJob($event->refund);
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
