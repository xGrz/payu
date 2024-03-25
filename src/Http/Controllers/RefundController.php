<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Http\Requests\StoreRefundRequest;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Models\Transaction;

class RefundController extends Controller
{

    public function create(Transaction $transaction)
    {
        return view('payu::refunds.create', [
            'title' => 'Create refund',
            'transaction' => $transaction,
            'products' => $transaction->payload['products'],
            'transactionAmount' => $transaction->payload['totalAmount'] / 100,
            'refunded' => $transaction->refunded()
        ]);
    }

    public function store(StoreRefundRequest $request, Transaction $transaction)
    {
        $refunded = PayU::refund(
            $transaction,
            $request->validated('amount'),
            $request->validated('description'),
            $request->validated('bankDescription', null)
        );
        return back()->with(
            $refunded ? 'success' : 'error',
            $refunded ? 'Refund created' : 'Cannot create refund'
        );
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
