<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use xGrz\PayU\Facades\PayU;
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


    public function store(ShopPayoutRequest $request): RedirectResponse
    {
        // TODO Tutaj skończyłem.
        $payoutAmountInCents = $request->validated('payoutAmount') * 100;

        $payout = PayoutRequest::callApi($payoutAmountInCents);
        Payout::create($payout->toArray());

        return to_route('payu.payouts.index');
    }


}
