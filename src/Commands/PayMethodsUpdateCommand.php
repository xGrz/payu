<?php

namespace xGrz\PayU\Commands;

use Illuminate\Console\Command;
use xGrz\PayU\Actions\SyncPaymentMethods;
use xGrz\PayU\Models\Method;

class PayMethodsUpdateCommand extends Command
{
    protected $signature = 'payu:sync-methods';
    protected $description = 'Synchronize payment methods';

    public function handle(): int
    {
        if (SyncPaymentMethods::handle()) {
            $methods = Method::select(['name', 'code', 'available', 'min_amount', 'max_amount'])
                ->orderByDesc('available')
                ->get()
                ->map(function ($method) {
                    $method->available = $method->available ? 'Available' : 'Disabled';
                    $method->minAmount = sprintf("%01.2f", $method->min_amount);
                    $method->maxAmount = sprintf("%01.2f", $method->max_amount);
                    return $method;
                })
                ->makeHidden(['min_amount', 'max_amount'])
                ->toArray();

            $this->info('Synchronized successfully with PayU.');
            $this->table(['Method name', 'Code', 'Status', 'Min. amount', 'Max amount'], $methods);
            $this->newLine();
            return Command::SUCCESS;
        } else {
            $this->error('Failed');
            return Command::FAILURE;
        }
    }
}

