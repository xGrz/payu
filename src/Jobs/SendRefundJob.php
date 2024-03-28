<?php

namespace xGrz\PayU\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Models\Refund;

class SendRefundJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Refund $refund)
    {
    }

    /**
     * @throws PayUGeneralException
     */
    public function handle(): void
    {
        if (!$this->refund->status->actionAvailable('send')) {
            throw new PayUGeneralException('Refund send failed. [Send] action unavailable');
        }

//        $response = SendPayoutRequest::callApi($this->payout->amount * 100)?->asObject();
//        $this->payout->update([
//            'payout_id' => $response->payout_id,
//            'status' => $response->status,
//        ]);
//
//        PayU::payoutStatusCheck($this->payout);
    }

    public function uniqueId(): string
    {
        return 'PayoutRequest:' . $this->refund->id;
    }
}
