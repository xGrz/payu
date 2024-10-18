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

(*) All unavailable features are limited by PayU API, not by package.

If you are running in personal sandbox or production environment you should manually run:

```
php artisan payu:sync-methods
```

This method will run automatically in Laravel scheduler (every day) for payment methods synchronizing with local database (performance
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
$transaction = PayU::createPayment($transactionWizard);
```

If payment method fail PayUGeneralException will be thrown, otherwise Transaction model will be returned.

From transaction model you can get payment link (`$transaction->link`) to PayU payment site. If you are not redirecting user automatically to PayU and 
you want to give link for user please notice to display it in blade as unescaped data `{!! $transaction->link !!}`. Displaying link as escaped will broke link parameters and transaction will not work.

Transaction model stores current status in `$transaction->status` enum. It is updated in background when notification webhook is received from PayU system.
Notifications are signed with secure keys. Any data manipulations are not allowed by webhook controller. Incoming notifications has middleware that checks is request ip is on whitelist too.


## Handling payment

Payment status is updated by notification webhook. 
PayU allows you to get manual or auto-accept payments for each payment method individually. You can set it in PayU panel (sandbox panel too).

When automatic accept is turned on you should get COMPLETED or CANCELED status depends on user transaction status.
In case of manual accepting you will get WAITING_FOR_CONFIRMATION status. In that case you can ACCEPT or REJECT payment.

```
use xGrz\PayU\Facades\PayU;

// accept
PayU::accept($transaction);

// reject
PayU::reject($transaction);
```

`$transaction` is eloquent model returned from `PayU::createPayment($transactionWizard);` method.

## Refunds

When transaction is completed you can make a refund to this transaction.

To start new refund you can write: 
```
use xGrz\PayU\Facades\PayU;

PayU::refund($transaction, $amount, $description, $bankDescription);
```

* `$transaction` is a payment with status COMPLETED  - required
* `$amount` is amount to refund (for ex. 100.99) - required
* `$description` is a reason of refund (for ex. 'RMA') - required
* `$bankDescription` is part of bank account refund title given by PayU bank account transfer (optional)

If you accidentally refunded wrong amount you have some time to cancel refund (see Config section).
While refund has status INITIALIZED or SCHEDULED you can delete it by calling:

```
use xGrz\PayU\Facades\PayU;

PayU::cancelRefund($refund);
```

Refund status is automatically updated by notification webhook.
Important! If someone defines refund in PayU panel it will appear on list when is completed. 

## Configuration

In installation section we have published config.
You can find it in config directory (Laravel default path is /config) - please search payu.php.

Default configuration is given in this config file. 
In jobs section you can configure delays for sending/retrying refunds and payouts (values are in seconds).
It is recommended to give at least 60 seconds delay on sending refunds/payouts. This time is given to admin in case of wrong amount given in form.

`transaction_method_check` is retrieving payment method of transaction (not available for public sandbox api).

As payouts are not notified by webhook our plugin will periodically check status od payouts. Fill free to set your on status check interval much longer then default. 


