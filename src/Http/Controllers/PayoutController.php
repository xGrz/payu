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
            'payouts' => Payout::latest()->get(),
            'balance' => PayU::balance()?->asObject()
        ]);
    }


    public function store(PayoutRequest $request): RedirectResponse
    {
        $payout = PayU::payout($request->validated('payoutAmount'));
        return to_route('payu.payouts.index')->with(
            $payout ? 'success' : 'error',
            $payout ? 'Payout has been initialized' : 'Payout not initialed'
        );
    }


}
