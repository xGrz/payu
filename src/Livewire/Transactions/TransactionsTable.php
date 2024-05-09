<?php

namespace xGrz\PayU\Livewire\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use xGrz\PayU\Models\Transaction;

class TransactionsTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('payu::transactions.livewire.transactions-table', [
            'transactions' => Transaction::latest()->paginate()
        ]);
    }
}
