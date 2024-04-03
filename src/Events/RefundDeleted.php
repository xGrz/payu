<?php

namespace xGrz\PayU\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use xGrz\PayU\Models\Refund;

class RefundDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Refund $refund)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
