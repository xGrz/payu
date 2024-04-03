<?php

namespace xGrz\PayU\Observers;

use xGrz\PayU\Enums\RefundStatus;
use xGrz\PayU\Events\RefundCompleted;
use xGrz\PayU\Events\RefundCreated;
use xGrz\PayU\Events\RefundDeleted;
use xGrz\PayU\Events\RefundFailed;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Traits\WithStatusChangeObserver;

class RefundObserver
{
    use WithStatusChangeObserver;

    public function created(Refund $refund): void
    {
        RefundCreated::dispatch($refund);
    }

    public function updating(Refund $refund): void
    {
        self::whenStatusChangedTo($refund, RefundStatus::ERROR, RefundFailed::class);
        self::whenStatusChangedTo($refund, RefundStatus::CANCELED, RefundFailed::class);
        self::whenStatusChangedTo($refund, RefundStatus::FINALIZED, RefundCompleted::class);
        self::clearErrorMessage($refund, RefundStatus::ERROR);
    }

    public function deleting(Refund $refund): void
    {
        RefundDeleted::dispatch($refund);
    }


}
