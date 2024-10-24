<?php

namespace xGrz\PayU\Services;

use Illuminate\Database\Eloquent\Collection;
use xGrz\PayU\Api\Exceptions\PayUPaymentException;
use xGrz\PayU\Enums\PaymentStatus;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Interfaces\PayUPaymentsInterface;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Models\Transaction;

class PayUPaymentService
{
    private PayUPaymentsInterface $model;

    public function __construct(PayUPaymentsInterface $model)
    {
        $this->model = $model;
    }

    public function hasActiveTransaction(): bool
    {
        return $this
            ->model
            ->payuable
            ->filter(function (Transaction $transaction) {
                if ($transaction->status->hasAction('processing')) return true;
                if ($transaction->status->hasAction('success')) return true;
                return false;
            })
            ->isNotEmpty();
    }

    public function createTransaction(?TransactionWizard $transactionWizard = null): bool
    {
        if ($this->hasActiveTransaction()) return false;
        return self::setupNewTransaction($transactionWizard ?? $this->model->payuTransactionWizard());
    }

    private function setupNewTransaction(?TransactionWizard $transactionWizard = null): bool
    {
        if ($payment = PayU::createPayment($transactionWizard ?? $this->model->payuTransactionWizard())) {
            $payment->payuable()->associate($this->model);
            $payment->save();
            return true;
        }
        throw new PayUPaymentException('Transaction could not be created.');
    }

    public function payuCreateTransaction(?TransactionWizard $transactionWizard = null): bool
    {
        if (self::hasActiveTransaction()) return false;
        return self::setupNewTransaction($transactionWizard ?? $this->model->payuTransactionWizard());
    }

    private function currentTransaction(): ?Transaction
    {
        return $this->model->payuable?->first();
    }

    public function canResetTransaction(): bool
    {
        return (bool)self::currentTransaction()?->status->hasAction('reset');
    }

    public function hasTransactions(): bool
    {
        return $this->model->payuable->count() > 0;
    }

    public function acceptPayment(): bool
    {
        return self::currentTransaction()
            && PayU::accept(self::currentTransaction());
    }

    public function rejectPayment(): bool
    {
        return self::currentTransaction()
            && PayU::reject(self::currentTransaction());
    }

    public function cancelPayment(): bool
    {
        return self::currentTransaction()
            && PayU::cancelTransaction(self::currentTransaction());
    }

    public function statusDetails(string $key = null): array|string
    {
        $status = self::currentTransaction()?->status;
        if (!$status) return $key ? '' : [];
        $statusData = [
            'name' => __('payu::transactions.status.' . $status->name),
            'code' => $status->name,
            'value' => $status->value,
            'actions' => $status->actions(),
            'status' => $status->status,
        ];
        return $key
            ? $statusData[$key]
            : $statusData;
    }

    public function status(): ?PaymentStatus
    {
        return self::currentTransaction()?->status;
    }

    public function link(): ?string
    {
        if (!self::currentTransaction()?->status->hasAction('pay')) return null;
        return self::currentTransaction()?->link ?? null;
    }

    public function hasAction($actionName): bool
    {
        return self::currentTransaction()
            && self::currentTransaction()?->status->hasAction($actionName);
    }

    public function history(): Collection
    {
        return $this->model->payuable;
    }

    public function resetTransaction(?TransactionWizard $transactionWizard = null): bool
    {
        if (!self::canResetTransaction()) return false;
        if (!PayU::resetTransaction(self::currentTransaction())) return false;
        return self::setupNewTransaction($transactionWizard ?? $this->model->payuTransactionWizard());
    }

    public function hasRefunds(): bool
    {
        return self::currentTransaction()
            && self::currentTransaction()->refunds->count();
    }

    public function refund(int|float $amount, string $description, string $bankDescription = ''): bool
    {
        return self::currentTransaction()
            && PayU::refund(self::currentTransaction(), $amount, $description, $bankDescription);
    }

    public function cancelRefund(int $refundId): bool
    {
        $refund = Refund::find($refundId);
        if (!$refund->status->hasAction('delete')) return false;
        return PayU::cancelRefund($refund);
    }

    public function refunds()
    {
        return self::currentTransaction()?->refunds ?? [];
    }
}
