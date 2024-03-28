<?php

namespace xGrz\PayU\Actions;

use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Models\Payout;

class UpdatePayoutState
{
    public static function handle(): void
    {
        $payouts = Payout::whereIn('status', PayoutStatus::updatable())->get();
        foreach ($payouts as $payout) {
            PayU::payoutStatusCheck($payout);
        }
    }

}
