<?php

namespace xGrz\PayU\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\PayU\Api\Actions\GetPayoutStatus;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Models\Payout;

class UpdatePayoutStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(public Payout $payout)
    {
    }

    public function handle(): void
    {
        if (!$this->payout->status->hasAction('processing')) {
            throw new PayUGeneralException('Payout status update failed. [Processing] action unavailable');
        }
        try {
            $payoutStatus = GetPayoutStatus::callApi($this->payout->payout_id);
            $this->payout->update([
                'status' => $payoutStatus->getStatus()
            ]);
        } catch (PayUGeneralException $e) {
            $this->payout->update([
                'status' => PayoutStatus::CANCELED,
                'error' => $e->getReason()
            ]);
            return;
        }

        if ($this->payout->status->hasAction('processing')) {
            PayU::forceUpdatePayoutStatus($this->payout);
        }
    }

}
