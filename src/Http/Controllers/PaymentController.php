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
            'transactions' => Transaction::orderBy('created_at', 'desc')->get()
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
            'transaction' => $transaction
        ]);
    }

    public function accept(Transaction $transaction)
    {
        PayU::accept($transaction);
        // TODO: add flash message
        return back();
    }

    public function reject(Transaction $transaction)
    {
        PayU::reject($transaction);
        // TODO: add flash message
        return back();
    }

}
