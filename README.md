# Laravel PayU plugin by xGrz

## Requirements
This package requires queue, scheduler and cache configured in your Laravel project.

## Installation

Install package via composer, publish config and run migrations:

```
composer require xgrz/payu

php artisan payu:config
php artisan migrate
```

Update your .env file with:

```
PAYU_SHOP_ID=
PAYU_MERCHANT_POS_ID=
PAYU_SIGNATURE_KEY=
PAYU_O_AUTH_CLIENT_ID=
PAYU_O_AUTH_CLIENT_SECRET=
```

Please fill all values with your PayU account settings.
You can leave it without values when you want to use PayU public sandbox (same functions are limited).
If you want to use all functions you can use personal sandbox account.

Using personal sandbox for testing is recommended. See what features are limited in the table below.

| Features                                         | Public sandbox | Personal sandbox | Production |
|--------------------------------------------------|----------------|------------------|------------|
| Payments                                         | Available      | Available        | Available  |
| Refunds                                          | Available      | Available        | Available  |
| Payouts                                          | No*            | Available        | Available  |
| Payment methods                                  | No*            | Available        | Available  |
| Account balance                                  | No*            | Available        | Available  |
| Select payment method while creating transaction | No*            | Available        | Available  |

All unavailable features are limited by PayU API.

If you are running in personal sandbox or production environment you should run:

```
php artisan payu:update-methods
```

This method will run in Laravel scheduler (every day) for payment methods synchronizing with local database (performance
reason).

## Create payment

We provide a TransactionWizard facade for easier payment creation.
There are some helpers too: `Products`, `Product`, `Buyer`, (`Address`|`PostalBox`). Wizard and helper object's can be
created by `new CLASS_NAME($args)` or `CLASS_NAME::make($args)`;

For example lets create `Buyer`:
```
use xGrz\PayU\Facades\TransactionWizard\Buyer;

$buyer = Buyer::make($email, $phone, $firstName, $lastName, $language, $customerId);
```

Every transaction requires only `Products` and _description_ to be sent to Api, however for better user experience we suggest to provide at least `Buyer` too.

Prepare transaction with TransactionWizard:
```
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Facades\TransactionWizard\Product;
use xGrz\PayU\Facades\TransactionWizard\Products;

$transaction = TransactionWizard(
    $description,
    Products::make([
        Product::make(name: 'First Product', unitPrice: 100, quantity: 2),
        Product::make(name: 'Second Product', unitPrice: 99.99, quantity: 1),
        Product::make(name: 'DHL Delivery', unitPrice: 19.99, quantity: 1, isVirtual: true);
    ]),
    Buyer::make('example@example.com', '500 600 700', 'John', 'Kovalsky', 'pl', 120),
);
```
Optional:
```
$transaction->setVisibleDescription('You are paying for order XXXX/XX');
```






