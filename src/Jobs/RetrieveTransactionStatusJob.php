<?php

namespace xGrz\PayU\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\PayU\Api\Actions\GetTransactionStatus;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Services\LoggerService;

class RetrieveTransactionStatusJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(public Transaction $transaction)
    {
    }

    public function handle(): void
    {
        $prevStatus = $this->transaction->status;
        try {
            $response = GetTransactionStatus::callApi($this->transaction);
            $this->transaction->update([
                'status' => $response->getStatus(),
            ]);

            if ($prevStatus !== $response->getStatus()) {
                LoggerService::warning('Transaction was not updated by PayU notification',[
                    'transaction' => $this->transaction->attributesToArray(),
                    'stats' => [
                        'prev' => $prevStatus->name,
                        'current' => $response->getStatus()->name
                    ],
                ]);
            }
        } catch (PayUGeneralException $e) {
            LoggerService::error('Long duration transaction without status change.', [
                'transaction' => $this->transaction->attributesToArray(),
                'currentStatus' => $this->transaction->status->name,
                'errorMessage' => $e->getMessage(),
            ]);
        }
    }

    public function uniqueId(): string
    {
        return 'TransactionStatusRequest:' . $this->transaction->id;
    }

    public function displayName(): string
    {
        return 'TransactionStatusRequestJob';
    }


}
