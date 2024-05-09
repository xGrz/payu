<?php

namespace xGrz\PayU\Http\Controllers;

use xGrz\PayU\Models\Refund;

class RefundController extends BaseController
{
    public function __invoke()
    {
        return view('payu::refunds.index', [
            'title' => 'Refunds',
            'refunds' => Refund::with(['transaction'])->latest()->paginate()
        ]);
    }

}
