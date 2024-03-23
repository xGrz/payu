<?php

namespace xGrz\PayU\Api\Notification;

use Illuminate\Support\Facades\Log;
use xGrz\PayU\Api\Exceptions\PayUResponseException;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Exceptions\StatusNameException;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Services\SignatureService;

class TransactionStatusNotificationHandler
{
    private PaymentStatus $prevStatus;
    private PaymentStatus $nextStatus;

    private bool $wasUpdated = false;

    /**
     * @throws PayUResponseException
     * @throws StatusNameException
     */
    private function __construct(private readonly Transaction $transaction, array $order)
    {
        if(!SignatureService::verify()) throw new PayUResponseException('Invalid PayU signature', 401);

        $this
            ->discoverPrevStatus()
            ->discoverNextStatus($order['status'])
            ->updateStatus();
    }

    private function discoverPrevStatus(): static
    {
        $this->prevStatus = $this->transaction->status;
        return $this;
    }

    /**
     * @throws StatusNameException
     */
    private function discoverNextStatus(string $nextStatus): static
    {
        $this->nextStatus = PaymentStatus::findByName($nextStatus);
        return $this;
    }

    private function updateStatus(): static
    {
        if ($this->prevStatus === $this->nextStatus) return $this;

        $this->transaction->update(['status' => $this->nextStatus]);
        $this->wasUpdated = true;
        Log::withContext([
            'transaction_id' => $this->transaction->id,
            'prevStatus' => $this->prevStatus->name,
            'currentStatus' => $this->nextStatus->name,
        ])->info('PayU Transaction | Status updated');
        return $this;
    }

    public function updated(): bool
    {
        return $this->wasUpdated;
    }

    public function currentStatus(): PaymentStatus
    {
        return $this->nextStatus;
    }

    /**
     * @throws PayUResponseException
     */
    public static function consumeNotification(Transaction $transaction, array $order): TransactionStatusNotificationHandler
    {
        return new TransactionStatusNotificationHandler($transaction, $order);
    }
}
