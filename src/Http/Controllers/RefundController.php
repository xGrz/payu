<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\PayU\Http\Requests\StoreRefundRequest;

class RefundController extends Controller
{

    public function create(Transaction $transaction)
    {
        return view('payu::refunds.create', [
            'title' => 'Make a refund',
            'transaction' => $transaction,
            'products' => $transaction->payload['products'],
            'transactionAmount' => $transaction->payload['totalAmount'] / 100,
            'refunded' => $transaction->refunded()
        ]);
    }

    public function store(StoreRefundRequest $request, Transaction $transaction)
    {
        CreateRefundToTransaction::handle(
            $transaction,
            $request->validated('amount'),
            $request->validated('description'),
            $request->validated('bankDescription', null)
        );
        return redirect()->back();
    }

    public function destroy(Refund $refund)
    {
        if (!$refund->status->isDeletable()) {
            return back()->with('error', 'Sorry, refund request has been already sent.');
        }
        $refund->delete();
        return back()->with('success', 'Refund successfully canceled');
    }

}
