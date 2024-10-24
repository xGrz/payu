<?php

namespace xGrz\PayU\Listeners;

use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Events\PayoutCreated;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Jobs\SendPayoutJob;

class DispatchPayoutRequest
{
    public function handle(PayoutCreated $event): void
    {
        $event
            ->payout
            ->updateQuietly(['status' => PayoutStatus::SCHEDULED]);

        SendPayoutJob::dispatch($event->payout)
            ->delay(Config::getPayoutSendDelay());
    }
}
