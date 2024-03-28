<?php

namespace xGrz\PayU\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\PayU\Api\Actions\SendRequestRefund;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Enums\RefundStatus;
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

        $response = SendRequestRefund::callApi($this->refund)?->asObject();
        $this->refund->update([
            'refund_id' => $response->refund_id,
            'status' => RefundStatus::findByName($response->status),
        ]);

    }

    public function uniqueId(): string
    {
        return 'PayoutRequest:' . $this->refund->id;
    }
}
