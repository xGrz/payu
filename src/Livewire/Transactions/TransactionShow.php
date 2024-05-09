<?php

namespace xGrz\PayU\Livewire\Transactions;

use Illuminate\View\View;
use Livewire\Component;
use xGrz\PayU\Models\Transaction;

class TransactionShow extends Component
{
    public Transaction $transaction;

    public function mount(Transaction $transaction): void
    {
        $this->transaction = $transaction;
    }


    public function render(): View
    {
        return view('payu::transactions.livewire.transaction-show');
    }
}
