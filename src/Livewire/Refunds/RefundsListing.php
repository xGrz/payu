<?php

namespace xGrz\PayU\Livewire\Refunds;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Models\Transaction;

class RefundsListing extends Component
{
    public Transaction $transaction;
    public string $tableTitle = '';
    public bool $shouldRenderAction = false;

    public function mount(Transaction $transaction, string $tableTitle, bool $shouldRenderAction = false): void
    {
        $this->transaction = $transaction;
        $this->tableTitle = $tableTitle;
        $this->shouldRenderAction = $shouldRenderAction;
    }

    public function openCreateRefundForm()
    {

    }

    #[On('refunds-updated')]
    public function refreshTransaction()
    {
        $this->transaction->refresh();
    }

    public function render(): View
    {
        return view('payu::refunds.livewire.refunds-listing');
    }

    public function retryRefund($refundId): void
    {
        $refund = $this->transaction->refunds->filter(function (Refund $refund) use ($refundId) {
            return $refund->id === $refundId;
        })->first();

        PayU::retryRefund($refund)
            ? session()->flash('warning', __('payu::refunds.retry.success'))
            : session()->flash('error', __('payu::refunds.retry.failed'));

        $this->redirectRoute('payu.payments.show', $this->transaction);
    }

    public function deleteRefund($refundId): void
    {
        $refund = $this->transaction->refunds->filter(function (Refund $refund) use ($refundId) {
            return $refund->id === $refundId;
        })->first();

        PayU::cancelRefund($refund)
            ? session()->flash('warning', __('payu::refunds.destroy.success'))
            : session()->flash('error', __('payu::refunds.destroy.failed'));

        $this->redirectRoute('payu.payments.show', $this->transaction);

    }

}
