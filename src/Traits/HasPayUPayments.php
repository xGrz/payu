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
    public function payable(): MorphMany
    {
        /* Latest sorting is required. Do not remove/change it */
        return $this
            ->morphMany(Transaction::class, 'payuable')
            ->latest();
    }

    /**
     * @throws PayUPaymentException
     */
    public function createTransaction(TransactionWizard $transactionWizard): bool
    {
        if (self::hasActiveTransaction()) return false;
        return self::setupNewTransaction($transactionWizard);
    }

    public function canResetTransaction(): bool
    {
        if (!self::hasActiveTransaction()) return false;
        if (!self::hasTransactions()) return false;
        return (bool)self::getTransaction()?->status->hasAction('reset');
    }

    /**
     * @throws PayUPaymentException
     */
    public function resetTransaction(TransactionWizard $transactionWizard): bool
    {
        if (!self::canResetTransaction()) return false;
        if (!self::cancelPayment()) return false;
        return self::setupNewTransaction($transactionWizard);
    }


    public function hasActiveTransaction(): bool
    {
        return (bool)$this
            ->payable
            ->filter(function (Transaction $transaction) {
                if ($transaction->status->hasAction('processing')) return true;
                if ($transaction->status->hasAction('success')) return true;
                return false;
            })->count();
    }

    public function hasTransactions(): bool
    {
        return $this->payable->count() > 0;
    }

    public function acceptPayment(): bool
    {
        if (!self::getTransaction()) return false;
        return PayU::accept(self::getTransaction());
    }

    public function rejectPayment(): bool
    {
        return self::getTransaction() && PayU::reject(self::getTransaction());
    }

    public function cancelPayment(): bool
    {
        return self::getTransaction() && PayU::cancelTransaction(self::getTransaction());
    }

    public function paymentStatus(string $key = null): array|string
    {
        $status = self::getTransaction()?->status;
        if (!$status) return [];
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

    public function paymentLink(): ?string
    {
        if (!self::getTransaction()?->status->hasAction('pay')) return null;
        return self::getTransaction()?->link;
    }

    public function hasPaymentAction($actionName): bool
    {
        return (bool)self::getTransaction()?->status->hasAction($actionName);
    }

    public function paymentHistory(): Collection
    {
        return $this->payable;
    }

    public function refunds()
    {
        if (!self::getTransaction()?->status->hasAction('refund')) return [];
        return $this->payable->first()?->refunds;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->payable->first();
    }

    public function createRefund(int|float $amount, string $description, string $bankDescription = ''): bool
    {
        $transaction = self::getTransaction();
        if (!$transaction) return false;
        return PayU::refund(self::getTransaction(), $amount, $description, $bankDescription);
    }

    private function cancelRefund(int $refundId): bool
    {
        $refund = Refund::find($refundId);
        if (!$refund->status->hasAction('delete')) return false;
        return PayU::cancelRefund($refund);
    }

    private function setupNewTransaction(TransactionWizard $transactionWizard): bool
    {
        if ($payment = PayU::createPayment($transactionWizard)) {
            $payment->payuable()->associate($this);
            $payment->save();
            return true;
        }
        throw new PayUPaymentException('Transaction could not be created.');
    }




}
