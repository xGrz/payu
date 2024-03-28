<?php

namespace xGrz\PayU\Actions;

use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Jobs\SendPayoutJob;
use xGrz\PayU\Models\Payout;

class SendPayouts
{
    public static function handle()
    {
        $payouts = Payout::whereIn('status', PayoutStatus::sendable())->oldest()->get();
        foreach ($payouts as $payout) {
            dispatch(new SendPayoutJob($payout))->delay(30);
        }
    }
}
