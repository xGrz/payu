<?php

namespace xGrz\PayU\Interfaces;

use xGrz\PayU\Facades\TransactionWizard;

interface PayUPaymentsInterface
{
    public function payuTransactionWizard(): TransactionWizard;
}
