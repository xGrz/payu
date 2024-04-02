<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Facades\TransactionWizard\Product;
use xGrz\PayU\Http\Requests\StorePaymentRequest;
use xGrz\PayU\Models\Method;
use xGrz\PayU\Models\Transaction;


class PaymentController extends Controller
{

    public function index(): View
    {
        return view('payu::transactions.index', [
            'title' => 'Transactions',
            'transactions' => Transaction::latest()->paginate(),
            'balance' => PayU::balance()?->asObject()
        ]);
    }

    public function create(): View
    {
        return view('payu::transactions.create', [
            'title' => 'Transaction wizard',
            'customer' => [
                'name' => fake('pl_PL')->firstName() . ' ' . fake('pl_PL')->lastName(),
                'street' => fake('pl_PL')->streetName(),
                'house_number' => fake('pl_PL')->numberBetween(1, 200),
                'apartment_number' => fake()->boolean(40) ? fake('pl_PL')->numberBetween(1, 200) : '',
                'city' => fake('pl_PL')->city(),
                'postalCode' => fake('pl_PL')->postcode(),
                'email' => fake('pl_PL')->safeEmail(),
                'phone' => fake('pl_PL')->phoneNumber(),

            ],
            'products' => [
                ['name' => fake('pl_PL')->words(2, true), 'quantity' => rand(1, 2), 'price' => rand(1, 20000) / 100],
                ['name' => fake('pl_PL')->words(2, true), 'quantity' => rand(1, 5), 'price' => rand(1, 20000) / 100],
                ['name' => fake('pl_PL')->words(2, true), 'quantity' => rand(2, 10), 'price' => rand(1, 20000) / 100],
            ],
            'methods' => Method::active()->amount(12000)->get()
        ]);
    }

    public function store(StorePaymentRequest $request)
    {
        $transaction = new TransactionWizard('Order ' . rand(1,2000) . '/2024');
        $items = new TransactionWizard\Products();
        foreach ($request->validated('items') as $item) {
            $items->add(Product::make($item['name'], $item['price'], $item['quantity']));
        }
        $buyer = new TransactionWizard\Buyer(
            $request->validated('customer.email'),
            $request->validated('customer.phone'),
            $request->validated('customer.name'),
            null,
            'pl',
        );

        $transaction->addProducts($items);
        $transaction->setBuyer($buyer);

        if ($request->validated('method')) {
            $transaction->setMethod( TransactionWizard\PayMethod::make($request->validated('method')));
        }

        PayU::createPayment($transaction);
        return to_route('payu.payments.index')->with('success', 'Transaction created');
    }

    public function storeFake()
    {
        $transaction = TransactionWizard::fake();
        PayU::createPayment($transaction);
        return back()->with('success', 'Transaction created');
    }

    public function show(Transaction $transaction)
    {
        return view('payu::transactions.show', [
            'title' => 'Transaction',
            'transaction' => $transaction
        ]);
    }

    public function accept(Transaction $transaction)
    {
        return PayU::accept($transaction)
            ? back()->with('success', 'Payment successfully accepted')
            : back()->with('error', 'Payment not accepted');
    }

    public function reject(Transaction $transaction)
    {
        return PayU::reject($transaction)
            ? back()->with('success', 'Payment successfully rejected')
            : back()->with('error', 'Payment was not rejected');
    }

    public function destroy(Transaction $transaction)
    {
        return PayU::cancelTransaction($transaction)
            ? back()->with('success', 'Payment successfully canceled')
            : back()->with('error', 'Payment was not deleted.');
    }

}
