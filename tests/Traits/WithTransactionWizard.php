<?php

namespace Traits;

use xGrz\PayU\Facades\TransactionWizard\Buyer;
use xGrz\PayU\Facades\TransactionWizard\Delivery\Address;
use xGrz\PayU\Facades\TransactionWizard\Delivery\PostalBox;
use xGrz\PayU\Facades\TransactionWizard\Product;
use xGrz\PayU\Facades\TransactionWizard\Products;

trait WithTransactionWizard
{
    private function getProducts(): Products
    {
        return Products::make([
            Product::make('Product 1', 100, 2),
            Product::make('Product 2', 200,),
            Product::make('Product 3', 400, .5)
        ]);
    }

    private function getAddressDelivery(): Address
    {
        return Address::make(
            '91-200',
            'Krakow',
            'Zakopianska 200/2',
            'PL',
            'test@example.com',
            'Jonathan Kovalsky',
            '198765432',
        );
    }

    private function getPostalBoxDelivery(): PostalBox
    {
        return PostalBox::make(
            'WA101',
            'test2@example.com',
            'Michael Novak',
            '600500200',
        );
    }

    private function getBuyer(): Buyer
    {
        return new Buyer(
            'example@example.com',
            '987567192',
            'John',
            'Travolta',
            199,
            'en',
        );
    }

}
