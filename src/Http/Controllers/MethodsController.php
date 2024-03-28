<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\PayU\Models\Method;

class MethodsController extends Controller
{
    public function __invoke()
    {
        return view('payu::methods.index', [
            'title' => 'Payment methods',
            'methods' => Method::all()
        ]);
    }
}
