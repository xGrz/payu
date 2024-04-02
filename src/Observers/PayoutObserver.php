<?php

namespace xGrz\PayU\Observers;


use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Events\PayoutCreated;
use xGrz\PayU\Models\Payout;

class PayoutObserver
{
    public function created(Payout $payout): void
    {
        PayoutCreated::dispatch($payout);
    }

    public function updating(Payout $payout): void
    {
        if ($payout->error && $payout->status !== PayoutStatus::CANCELED) {
            $payout->error = null;
        }

    }

}
