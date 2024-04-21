<?php

namespace xGrz\PayU\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Http\Requests\PayoutRequest;
use xGrz\PayU\Models\Payout;

class PayoutController extends BaseController
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
            ? to_route(Config::getRouteName('payouts.index'))->with('success', __('payu::payouts.create.success'))
            : to_route(Config::getRouteName('payouts.index'))->with('error', __('payu::payouts.create.failed'));
    }

    public function update(Payout $payout)
    {
        PayU::forceUpdatePayoutStatus($payout);
        return back()->with('success', __('payu::payouts.updateStatus.success'));
    }

    public function retry(Payout $payout)
    {
        return PayU::retryPayout($payout)
            ? back()->with('success', __('payu::payouts.retry.success'))
            : back()->with('error', __('payu::payouts.retry.failed'));
    }

    public function destroy(Payout $payout)
    {
        return PayU::cancelPayout($payout)
            ? back()->with('success', __('payu::payouts.destroy.success'))
            : back()->with('error', __('payu::payouts.destroy.failed'));
    }

}
