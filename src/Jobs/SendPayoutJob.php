<?php

namespace xGrz\PayU\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\PayU\Api\Actions\SendPayoutRequest;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Models\Payout;
use xGrz\PayU\Services\LoggerService;

class SendPayoutJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 2;

    public function __construct(public Payout $payout)
    {
    }

    /**
     * @throws PayUGeneralException
     */
    public function handle(): void
    {
        if (!$this->payout->status->hasAction('send')) {
            LoggerService::error('Payout dispatching failed', [
                'payout_status' => $this->payout->status->name,
                'actions' => $this->payout->status->actions(),
                'requested_action' => 'send'
            ]);
            throw new PayUGeneralException('Payout dispatching failed. [Send] action unavailable');
        }
        try {
          $response = SendPayoutRequest::callApi($this->payout->amount * 100)?->asObject();
            $this->payout->update([
                'payout_id' => $response->payout_id,
                'status' => $response->status,
            ]);
            PayU::forceUpdatePayoutStatus($this->payout);

        } catch (PayUGeneralException $e) {
            $this->payout->update([
                'status' => PayoutStatus::CANCELED,
                'error' => $e->getReason()
            ]);
        }

    }

    public function uniqueId(): string
    {
        return 'PayoutRequest:' . $this->payout->id;
    }
}
