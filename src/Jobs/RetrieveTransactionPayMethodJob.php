<?php

namespace xGrz\PayU\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use xGrz\PayU\Api\Actions\RetrieveTransactionPayMethod;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Services\LoggerService;

class RetrieveTransactionPayMethodJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function __construct(public Transaction $transaction)
    {
    }

    public function handle(): void
    {
        if(!Config::hasPayMethods()) {
            LoggerService::warning('Pay methods are not available (not synchronized)', [
                ''
            ]);
            return;
        }

        try {
            $payMethod = RetrieveTransactionPayMethod::callApi($this->transaction);

            $this->transaction->payMethod()->associate($payMethod->getMethod());
            $this->transaction->saveQuietly();

        } catch (PayUGeneralException $e) {
            LoggerService::error('Pay method retrieve failed');
        }
    }

    public function uniqueId(): string
    {
        return 'TransactionPayMethodRequest:' . $this->transaction->id;
    }


}
