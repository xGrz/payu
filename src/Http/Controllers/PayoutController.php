<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Http\Requests\PayoutRequest;
use xGrz\PayU\Models\Payout;

class PayoutController extends Controller
{

    public function index(): View
    {
        return view('payu::payouts.index', [
            'title' => 'Shop details',
            'payouts' => Payout::latest()->paginate(),
            'balance' => PayU::balance()?->asObject()
        ]);
    }


    public function store(PayoutRequest $request): RedirectResponse
    {
        return PayU::payout($request->validated('payoutAmount'))
            ? to_route('payu.payouts.index')->with('success', 'Payout has been scheduled')
            : to_route('payu.payouts.index')->with('error', 'Payout not initialed. Error occurred.');
    }

    public function update(Payout $payout)
    {
        PayU::payoutStatusCheck($payout);
        return back()->with('success', 'Payout status retry in progress');
    }

    public function retry(Payout $payout)
    {
        return PayU::retryPayout($payout, 3)
            ? back()->with('success', 'Retry payout has been dispatched')
            : back()->with('error', 'Retry payout failed');
    }

    public function destroy(Payout $payout)
    {
        return PayU::cancelPayout($payout)
            ? back()->with('success', 'Payout request has been successfully removed')
            : back()->with('error', 'Error! Payout request cannot be removed');
    }

}
