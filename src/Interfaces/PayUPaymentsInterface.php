<?php

namespace xGrz\PayU\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use xGrz\PayU\Facades\TransactionWizard;

interface PayUPaymentsInterface
{
    public function payuTransactionWizard(): TransactionWizard;

    public function getViewUrl(): string;

    public function payuable(): MorphMany;
}
