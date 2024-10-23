<?php

namespace xGrz\PayU\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use xGrz\PayU\Api\Exceptions\PayUPaymentException;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Models\Transaction;

trait HasPayUPayments
{
    /**
     * Relationship for PayU-Transaction
     * @return MorphMany
     */
    public function payuable(): MorphMany
    {
        /* Latest sorting is required. Do not remove/change it */
        return $this
            ->morphMany(Transaction::class, 'payuable')
            ->latest();
    }

    public function payuGetTransactionWiard(): TransactionWizard
    {
        return new TransactionWizard();
    }

    /**
     * @throws PayUPaymentException
     */
    public function payuCreateTransaction(?TransactionWizard $transactionWizard = null): bool
    {
        if (self::payuHasActiveTransaction()) return false;
        return self::payuSetupNewTransaction($transactionWizard ?? self::payuGetTransactionWiard());
    }

    public function payuCanResetTransaction(): bool
    {
        return (bool)self::payuGetTransaction()?->status->hasAction('reset');
    }

    /**
     * @throws PayUPaymentException
     */
    public function payuResetTransaction(?TransactionWizard $transactionWizard = null): bool
    {
        if (!self::payuCanResetTransaction()) return false;
        if (!PayU::resetTransaction(self::payuGetTransaction())) return false;
        return self::payuSetupNewTransaction($transactionWizard ?? self::payuGetTransactionWiard());
    }

    public function payuHasActiveTransaction(): bool
    {
        return (bool)$this
            ->payuable
            ->filter(function (Transaction $transaction) {
                if ($transaction->status->hasAction('processing')) return true;
                if ($transaction->status->hasAction('success')) return true;
                return false;
            })->count();
    }

    public function payuHasTransactions(): bool
    {
        return $this->payuable->count() > 0;
    }

    public function payuAcceptPayment(): bool
    {
        if (!self::payuGetTransaction()) return false;
        return PayU::accept(self::payuGetTransaction());
    }

    public function payuRejectPayment(): bool
    {
        return self::payuGetTransaction() && PayU::reject(self::payuGetTransaction());
    }

    public function payuCancelPayment(): bool
    {
        return self::payuGetTransaction() && PayU::cancelTransaction(self::payuGetTransaction());
    }

    public function payuPaymentStatus(string $key = null): array|string
    {
        $status = self::payuGetTransaction()?->status;
        if (!$status) return $key ? '' : [];
        $statusData = [
            'name' => __('payu::transactions.status.' . $status->name),
            'code' => $status->name,
            'value' => $status->value,
            'actions' => $status->actions(),
        ];
        return $key
            ? $statusData[$key]
            : $statusData;
    }

    public function payuPaymentLink(): ?string
    {
        if (!self::payuGetTransaction()?->status->hasAction('pay')) return null;
        return self::payuGetTransaction()?->link;
    }

    public function payuHasPaymentAction($actionName): bool
    {
        return (bool)self::payuGetTransaction()?->status->hasAction($actionName);
    }

    public function payuPaymentHistory(): Collection
    {
        return $this->payuable;
    }

    public function payuRefunds()
    {
        // if (!self::getTransaction()?->status->hasAction('refund')) return [];
        return self::payuGetTransaction()?->refunds ?? [];
    }

    public function payuGetTransaction(): ?Transaction
    {
        return $this->payuable->first();
    }

    public function payuGetPreviousTransaction(): ?Transaction
    {
        if ($this->payuable->count() < 2) return null;
        return $this->payuable[1];
    }

    public function payuHasRefunds(): bool
    {
        if(!self::payuHasTransactions()) return false;
        return (bool) self::payuGetTransaction()->refunds->count();
    }

    public function payuCreateRefund(int|float $amount, string $description, string $bankDescription = ''): bool
    {
        $transaction = self::payuGetTransaction();
        if (!$transaction) return false;
        return PayU::refund(self::payuGetTransaction(), $amount, $description, $bankDescription);
    }

    private function payuCancelRefund(int $refundId): bool
    {
        $refund = Refund::find($refundId);
        if (!$refund->status->hasAction('delete')) return false;
        return PayU::cancelRefund($refund);
    }

    private function payuSetupNewTransaction(?TransactionWizard $transactionWizard = null): bool
    {
        if ($payment = PayU::createPayment($transactionWizard ?? self::payuGetTransactionWiard())) {
            $payment->payuable()->associate($this);
            $payment->save();
            return true;
        }
        throw new PayUPaymentException('Transaction could not be created.');
    }


}
