<?php

namespace xGrz\PayU\Listeners;

use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Jobs\SendRefundJob;

class DispatchRefundRequest
{
    public function handle(RefundCreated $event): void
    {
        $event
            ->refund
            ->update(['status' => RefundStatus::SCHEDULED]);

        SendRefundJob::dispatch($event->refund)
            ->delay(Config::getRefundSendDelay());
    }
}
