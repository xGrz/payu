<?php

namespace xGrz\PayU\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\PayU\Api\Actions\GetPayoutStatus;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Models\Payout;
use xGrz\PayU\Services\LoggerService;

class UpdatePayoutStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Payout $payout)
    {
    }

    public function handle(): void
    {
        try {
            $payoutStatus = GetPayoutStatus::callApi($this->payout->payout_id);
        } catch (PayUGeneralException $e) {
            self::apiException($e);
            return;
        }

        $this->payout->fill(['status' => $payoutStatus->getStatus()]);


        if ($this->payout->isDirty('status')) {
            $this->payout->save();
            LoggerService::notice('Payout status updated', [
                'payout_id' => $this->payout->payout_id,
                'prevStatus' => $this->payout->status->name,
                'nextStatus' => $payoutStatus->getStatus()->name
            ]);
        }

        if ($this->payout->status->hasAction('retry')) {
            PayU::payoutStatusCheck($this->payout);
        }
    }

    private function apiException(PayUGeneralException $e): void
    {
        LoggerService::error('Payout status not updated', [
            'payout_id' => $this->payout->payoutId,
            'location' => 'ApiCall error',
            'reason' => $e->getMessage(),
        ]);

    }


}
