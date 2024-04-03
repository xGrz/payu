<?php

namespace xGrz\PayU\Observers;

use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Events\PayoutCompleted;
use xGrz\PayU\Events\PayoutCreated;
use xGrz\PayU\Events\PayoutDeleted;
use xGrz\PayU\Events\PayoutFailed;
use xGrz\PayU\Models\Payout;
use xGrz\PayU\Traits\WithStatusChangeObserver;

class PayoutObserver
{

    use WithStatusChangeObserver;

    public function created(Payout $payout): void
    {
        PayoutCreated::dispatch($payout);
    }

    public function updating(Payout $payout): void
    {
        self::whenStatusChangedTo($payout, PayoutStatus::REALIZED, PayoutCompleted::class);
        self::whenStatusChangedTo($payout, PayoutStatus::CANCELED, PayoutFailed::class);
        self::clearErrorMessage($payout, PayoutStatus::CANCELED);
    }

    public function deleting(Payout $payout): void
    {
        PayoutDeleted::dispatch($payout);
    }

}
