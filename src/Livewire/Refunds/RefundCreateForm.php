<?php

namespace xGrz\PayU\Livewire\Refunds;

use Illuminate\View\View;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Models\Transaction;
use xGrz\Qbp\Helpers\Money;

class RefundCreateForm extends ModalComponent
{
    #[Validate]
    public string $amount;
    #[Validate]
    public string $description = '';
    #[Validate]
    public string $bank_description = '';

    public Transaction $transaction;

    public function mount() {
        $this->amount = Money::from($this->transaction->maxRefundAmount())->format();
    }

    public function render(): View
    {
        return view('payu::refunds.livewire.refund-create-form');
    }

    public function updatingAmount($amount): void
    {
        Money::isValid($amount);
    }

    public function store()
    {
        $this->validate();
        $refunded = PayU::refund(
            $this->transaction,
            Money::from($this->amount)->toNumber(),
            $this->description,
            $this->bank_description
        );
        $this->closeModal();
        session()->flash('success', 'Refund has been created.');
        $this->dispatch('refunds-updated');
    }

    protected function prepareForValidation($attributes): array
    {
        $attributes['amount'] = Money::from($attributes['amount'])->toNumber();
        return $attributes;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|max:'.$this->transaction->maxRefundAmount(),
            'description' => 'required|string',
            'bank_description' => 'nullable|string',
        ];
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }
}
