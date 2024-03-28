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
        $payout = PayU::payout($request->validated('payoutAmount'));
        return $payout
            ? to_route('payu.payouts.index')->with('success', 'Payout has been scheduled')
            : to_route('payu.payouts.index')->with('error', 'Payout not initialed. Error occurred.');
    }

    public function update(Payout $payout)
    {
        PayU::payoutStatusCheck($payout);
        return back()->with('success', 'Payout status update in progress');
    }

    public function destroy(Payout $payout)
    {
        $canceled = PayU::cancelPayout($payout);

        return $canceled
            ? back()->with('success', 'Payout request has been successfully removed')
            : back()->with('error', 'Error! Payout request cannot be removed');
    }

}
