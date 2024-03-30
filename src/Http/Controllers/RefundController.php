<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Http\Requests\StoreRefundRequest;
use xGrz\PayU\Jobs\SendRefundJob;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Models\Transaction;

class RefundController extends Controller
{

    public function index()
    {
        return view('payu::refunds.index', [
            'title' => 'Refunds',
            'refunds' => Refund::with(['transaction'])->latest()->paginate()
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

    public function retry(Refund $refund)
    {
        PayU::retryRefund($refund, );
        SendRefundJob::dispatch($refund)->delay(Config::getRefundSendDelay());
        return back()->with('success', 'Retry refund send dispatched');
    }

    public function destroy(Refund $refund)
    {
        $canceled = PayU::cancelRefund($refund);

        return $canceled
            ? back()->with('success', 'Refund successfully canceled')
            : back()->with('error', 'Sorry, refund request has been already sent.');
    }

}
