<?php

namespace xGrz\PayU\Livewire;

use Livewire\Component;
use xGrz\PayU\Models\Transaction;

class TransactionsTable extends Component
{
    public function render()
    {
        return view('payu::transactions.transactions-table', [
            'transactions' => Transaction::latest()->paginate()
        ]);
    }
}
