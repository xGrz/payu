<?php

namespace xGrz\PayU\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use xGrz\PayU\Models\Transaction;
use xGrz\PayU\Services\PayUPaymentService;

trait HasPayUPayments
{

    public ?PayUPaymentService $payu = null;

    /**
     * Relationship for PayU-Transaction
     * @return MorphMany
     */
    public function payuable(): MorphMany
    {
        return $this
            ->morphMany(Transaction::class, 'payuable')
            ->latest(); // required - do not change it!
    }

    protected function initializeHasPayuPayments(): void
    {
        $this->payu = new PayUPaymentService($this);
    }

}
