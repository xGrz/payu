<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Models\Method;

class MethodsController extends Controller
{
    public function index()
    {
        return view('payu::methods.index', [
            'title' => 'Payment methods',
            'methods' => Method::orderBy('available', 'desc')->orderBy('name')->get()
        ]);
    }

    public function synchronize()
    {
        return PayU::syncMethods()
            ? back()->with('success', 'Payment methods successfully synchronized.')
            : back()->with('error', 'Error synchronizing payment methods.');

    }

}
