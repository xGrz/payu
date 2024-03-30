<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Models\Transaction;


class PaymentController extends Controller
{

    public function index(): View
    {
        return view('payu::transactions.index', [
            'title' => 'Transactions',
            'transactions' => Transaction::latest()->paginate(),
            'balance' => PayU::balance()?->asObject()
        ]);
    }

    public function store()
    {
        $transaction = TransactionWizard::fake();
        PayU::createPayment($transaction);
        return back()->with('success', 'Transaction created');
    }

    public function show(Transaction $transaction)
    {
        return view('payu::transactions.show', [
            'title' => 'Transaction',
            'transaction' => $transaction
        ]);
    }

    public function accept(Transaction $transaction)
    {
        return PayU::accept($transaction)
            ? back()->with('success', 'Payment successfully accepted')
            : back()->with('error', 'Payment not accepted');
    }

    public function reject(Transaction $transaction)
    {
        return PayU::reject($transaction)
            ? back()->with('success', 'Payment successfully rejected')
            : back()->with('error', 'Payment was not rejected');
    }

    public function destroy(Transaction $transaction)
    {
        return PayU::cancelTransaction($transaction)
            ? back()->with('success', 'Payment successfully canceled')
            : back()->with('error', 'Payment was not deleted.');
    }

}
