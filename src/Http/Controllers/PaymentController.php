<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\PayU\Api\Actions\CreatePaymentAction;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Models\Transaction;


class PaymentController extends Controller
{

    public function index()
    {

        return view('payu::transactions.index', [
            'title' => 'Transactions',
            'transactions' => Transaction::orderBy('created_at', 'desc')->paginate(),
            'balance' => PayU::balance()?->asObject()
        ]);
    }

    public function store()
    {
        $t = TransactionWizard::fake();
        return CreatePaymentAction::callApi($t);

    }

    public function show(Transaction $transaction)
    {
        $transaction->loadMissing(['refunds']);
        return view('payu::transactions.show', [
            'title' => 'Transaction',
            'transaction' => $transaction
        ]);
    }

    public function accept(Transaction $transaction)
    {
        $accepted = PayU::accept($transaction);
        return back()->with(
            $accepted ? 'success' : 'error',
            $accepted ? 'Payment successfully accepted' : 'Payment not accepted'
        );
    }

    public function reject(Transaction $transaction)
    {
        $rejected = PayU::reject($transaction);
        return back()->with(
            $rejected ? 'success' : 'error',
            $rejected ? 'Payment successfully rejected' : 'Payment was not rejected'
        );
    }

}
