<?php

namespace xGrz\PayU\Http\Controllers;

use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Http\Requests\StoreRefundRequest;
use xGrz\PayU\Models\Refund;
use xGrz\PayU\Models\Transaction;

class RefundController extends BaseController
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
        return $refunded
            ? back()->with('success', __('payu::refunds.create.success'))
            : back()->with('error', __('payu::refunds.create.failed'));
    }

    public function retry(Refund $refund)
    {
        return PayU::retryRefund($refund)
            ? back()->with('success', __('payu::refunds.retry.success'))
            : back()->with('error', __('payu::refunds.retry.failed'));
    }

    public function destroy(Refund $refund)
    {
        return PayU::cancelRefund($refund)
            ? back()->with('success', __('payu::refunds.destroy.success'))
            : back()->with('error', __('payu::refunds.destroy.failed'));
    }

}
