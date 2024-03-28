<?php

namespace xGrz\PayU\Observers;

use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Models\Refund;

class RefundObserver
{
    public function created(Refund $refund): void
    {
        RefundCreated::dispatch($refund);
    }

}
