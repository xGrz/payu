<?php

namespace xGrz\PayU\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use xGrz\PayU\Models\Transaction;

class TransactionsTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('payu::transactions.transactions-table', [
            'transactions' => Transaction::latest()->paginate()
        ]);
    }
}
