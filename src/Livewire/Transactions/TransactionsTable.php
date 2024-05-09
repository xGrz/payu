<?php

namespace xGrz\PayU\Livewire\Transactions;

use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Models\Transaction;

class TransactionsTable extends Component
{
    use WithPagination;

    public function render(): View
    {
        return view('payu::transactions.livewire.transactions-table', [
            'transactions' => Transaction::latest()->paginate()
        ]);
    }


    public function deleteTransaction($transactionId): void
    {
        $transaction = Transaction::find($transactionId);
        PayU::cancelTransaction($transaction)
            ? session()->flash('success', 'Payment has been successfully canceled')
            : session()->flash('error', 'Payment was not deleted.');
        $this->redirect(request()->header('Referer'));
    }

    public function acceptTransaction($transactionId): void
    {
        $transaction = Transaction::find($transactionId);
        PayU::accept($transaction)
            ? session()->flash('success', __('payu::transactions.accept.success'))
            : session()->flash('error', __('payu::transactions.accept.failed'));
        $this->redirect(request()->header('Referer'));
    }

    public function rejectTransaction($transactionId): void
    {
        $transaction = Transaction::find($transactionId);
        PayU::reject($transaction)
            ? session()->flash('success', __('payu::transactions.reject.success'))
            : session()->flash('error', __('payu::transactions.reject.failed'));
        $this->redirect(request()->header('Referer'));
    }
}
