# Laravel PayU plugin by xGrz

This package handles: `Payments`, `Refunds` and `Payouts`. 

## Requirements
This package requires queue, scheduler and cache configured in your Laravel project.

## Installation

Install package via composer, publish config and run migrations:

```
composer require xgrz/payu

php artisan payu:publish-config
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
xGrz\PayU\Facades\TransactionWizard\Delivery\Address;
xGrz\PayU\Facades\TransactionWizard\Delivery\PostalBox;

$transactionWizard = TransactionWizard(
    // Required - visible in PayU system (admin panel) 
    $description,
    
    // Required
    Products::make([
        // Product is required. 
        // Default value for quantity=1, isVirtual is set default to false.
        Product::make(name: 'First Product', unitPrice: 100, quantity: 2),
        Product::make(name: 'Second Product', unitPrice: 99.99, quantity: 1),
        Product::make(name: 'DHL Delivery', unitPrice: 19.99, quantity: 1, isVirtual: true);
    ]), 
    // Optional, but recommended. If you do not provide Buyer PayU payment site will ask for data from Buyer object.
    // For better user experience you shoud send Buyer object in transaction.
    Buyer::make('example@example.com', '500 600 700', 'John', 'Kovalsky', 'pl', 120), 
    
    // Delivery is optional. You can send Address delivery object of PostalBox delivery object depends on user choice.
    Address::make(
        postalCode: '02-020', 
        city: 'Katowice', 
        streetWithNumber: 'Wolska 201/21', 
        countryCode: 'PL', 
        recipientEmail: 're@example.com', 
        recipientFullName: 'Karol Novak',
        recipientPhone: '999666444'
    ),
    
    // or when your delivery is to postal boxes: 
    // PostalBox::make(postalBox: 'WA201',recipientEmail: 're@example.com', recipientFullName: 'Karol Novak', recipientPhone: '999666444'),
    
    // Optional, but strongly recommended is redirect url address after transaction
    // Redirect is individual for every transaction. When transaction fails payu will add ?error parameter to your url.
    redirectAfterTransaction: 'https://yoursite.com/order/summary'
    
    // Optional, only when you allow users to chose payment method on your site (not at PayU site)
    // You have to provide method code selected by customer. This is not working in public sandbox api.
    PayMethod::make(method: 'P'),
    
);
```
Optional you can add visible description (visible at PayU payment site):
```
$transaction->setVisibleDescription('You are paying for order XXXX/XX');
```

Once your transaction wizard is completed you can send it to PayU:
```
PayU::createPayment($transactionWizard);
```






