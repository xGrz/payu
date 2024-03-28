<?php

namespace xGrz\PayU\Observers;


use xGrz\PayU\Events\PayoutCreated;
use xGrz\PayU\Models\Payout;

class PayoutObserver
{
    public function created(Payout $payout): void
    {
        PayoutCreated::dispatch($payout);
    }

}
