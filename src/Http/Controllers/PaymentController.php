<?php

namespace xGrz\PayU\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Facades\PayU;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Facades\TransactionWizard\Product;
use xGrz\PayU\Http\Requests\StorePaymentRequest;
use xGrz\PayU\Jobs\RetrieveTransactionPayMethodJob;
use xGrz\PayU\Models\Method;
use xGrz\PayU\Models\Transaction;


class PaymentController extends BaseController
{

    public function index(): View
    {
        return view('payu::transactions.index', [
            'title' => 'Transactions',
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
            'methods' => Method::active()->get()
        ]);
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $transaction = new TransactionWizard('Order number ' . rand(1, 2000) . '/2024');
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
        $transaction->setDelivery(TransactionWizard\Delivery\Address::make(
            $request->validated('customer.postalCode'),
            $request->validated('customer.city'),
            str(join('/', [
                join(' ', [
                    $request->validated('customer.street'),
                    $request->validated('customer.house_number')
                ]),
                str($request->validated('customer.apartment_number'))->trim()
            ]))->whenEndsWith('/', fn($street) => str($street)->replaceLast('/', '')),
            'PL',
            $request->validated('customer.email'),
            $request->validated('customer.name'),
            $request->validated('customer.phone'),
        ));

        if ($request->validated('method')) {
            $transaction->setMethod(TransactionWizard\PayMethod::make($request->validated('method')));
        }

        PayU::createPayment($transaction);

        return to_route(Config::getRouteName('payments.index'))->with('success', __('payu::transactions.created'));
    }

    public function storeFake(): RedirectResponse
    {
        $transaction = TransactionWizard::fake();
        PayU::createPayment($transaction);
        return back()->with('success', __('payu::transactions.created'));
    }

    public function show(Transaction $transaction): View
    {
        return view('payu::transactions.show', [
            'title' => 'Transaction',
            'transaction' => $transaction
        ]);
    }


    public function requestPayMethod(Transaction $transaction): RedirectResponse
    {
        RetrieveTransactionPayMethodJob::dispatch($transaction)
            ->delay(Config::getTransactionMethodCheckDelay());

        return back()->with('success', 'Request sent. Please wait few seconds...');
    }

}
