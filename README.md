# Laravel PayU plugin

## Create payment


### Quick transaction wizard
> use xGrz\PayU\Facades\TransactionWizard;
> 
> $transaction = TransactionWizard::make(`Products`, `Buyer`, `Delivery`, `$urlToRedirectAfterTransaction`);

#### Products
Products object is wrapper for order items.
You can create products object with two ways:
```
use xGrz\PayU\Facades\TransactionWizard\Products;

$products = new Products();
// or
$products = Products::make();
```
You can pass array of `Product` objects as argument to quickly add items, for example:
```
use xGrz\PayU\Facades\TransactionWizard\Product;
use xGrz\PayU\Facades\TransactionWizard\Products;

$products = Products::make([
    xGrz\PayU\Facades\TransactionWizard\Product::make('Product A', 1199.99, 1),
    xGrz\PayU\Facades\TransactionWizard\Product::make('Product B', 109.99, 1),
    // ...
]);
```

Manually add products? Easy...

```
$products = Products::make();
$products->add(Product);
```


```
$t = TransactionWizard::make(
    TransactionWizard\Products::make([
        xGrz\PayU\Facades\TransactionWizard\Product::make('Product A', 1199.99, 1),
        xGrz\PayU\Facades\TransactionWizard\Product::make('Product B', 109.99, 1),
        xGrz\PayU\Facades\TransactionWizard\Product::make('Product C', 99.99, 2),
    ]),
    xGrz\PayU\Facades\TransactionWizard\Buyer::make('ab@examplecom', '123987627', 'John', 'Travolta', 'en', 100),
    xGrz\PayU\Facades\TransactionWizard\Delivery\PostalBox::make('ab@examplecom', 'John Travolta', '123987627', 'WA101'),
    route('home')
);

```
