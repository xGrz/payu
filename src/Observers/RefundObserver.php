<?php

namespace xGrz\PayU\Observers;

use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Models\Refund;

class RefundObserver
{
    public function created(Refund $refund): void
    {
        RefundCreated::dispatch($refund);
    }

    public function updating(Refund $refund)
    {
        if ($refund->error && $refund->status !== RefundStatus::ERROR) {
            $refund->error = null;
        }
    }

}
