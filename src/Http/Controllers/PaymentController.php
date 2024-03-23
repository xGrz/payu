<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\PayU\Api\Actions\CreatePaymentAction;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Facades\TransactionWizard\Product;
use xGrz\PayU\Models\Transaction;


class PaymentController extends Controller
{

    public function index()
    {
        return view('payu::transactions.index', [
            'transactions' => Transaction::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function create()
    {

    }

    public function store()
    {
        $t = TransactionWizard::fake();
        return CreatePaymentAction::callApi($t);

    }


    public function index2()
    {
        $t = TransactionWizard::make(
            'Zamówienie 200',
            TransactionWizard\Products::make([
                Product::make('Product A', 1199.99, 1),
                Product::make('Product B', 109.99, 1),
                Product::make('Product C', 99.99, 2),
            ]),
            TransactionWizard\Buyer::make('ab@example.com', '123987627', 'John', 'Travolta', 'en', 100),
            TransactionWizard\Delivery\PostalBox::make('ab@example.com', 'John Travolta', '123987627', 'WA101'),
            route('home')
        );

        return CreatePaymentAction::callApi($t);

    }

}
