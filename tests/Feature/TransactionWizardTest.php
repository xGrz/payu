<?php

require_once(__DIR__ . '/../Traits/WithTransaction.php');

use Tests\TestCase;
use Traits\WithTransaction;
use xGrz\PayU\Facades\TransactionWizard;
use xGrz\PayU\Facades\TransactionWizard\Product;

class TransactionWizardTest extends TestCase
{
    use WithTransaction;

    public TransactionWizard $transaction;

    public function setUp(): void
    {
        parent::setUp();
        $this->transaction = new TransactionWizard();
    }

    public function test_wizard_basic_setup_customer_ip()
    {
        $this->assertArrayHasKey('customerIp', $this->transaction->toArray());
        $this->assertNotEmpty($this->transaction->toArray()['customerIp']);
    }

    public function test_wizard_basic_setup_merchant_pos_id()
    {
        $this->assertArrayHasKey('merchantPosId', $this->transaction->toArray());
        $this->assertNotEmpty($this->transaction->toArray()['merchantPosId']);
        $this->assertIsNumeric($this->transaction->toArray()['merchantPosId']);
    }

    public function test_wizard_basic_setup_description()
    {
        $this->assertArrayHasKey('description', $this->transaction->toArray());
        $this->assertEmpty($this->transaction->toArray()['description']);
    }

    public function test_wizard_basic_setup_currency_code()
    {
        $this->assertArrayHasKey('currencyCode', $this->transaction->toArray());
        $this->assertEquals('PLN', $this->transaction->toArray()['currencyCode']);
    }

    public function test_wizard_basic_setup_total_amount()
    {
        $this->assertArrayHasKey('totalAmount', $this->transaction->toArray());
        $this->assertIsNumeric($this->transaction->toArray()['totalAmount']);
        $this->assertEquals(0, $this->transaction->toArray()['totalAmount']);
    }

    public function test_wizard_basic_setup_products()
    {
        $this->assertArrayHasKey('products', $this->transaction->toArray());
        $this->assertIsArray($this->transaction->toArray()['products']);
        $this->assertCount(0, $this->transaction->toArray()['products']);
    }

    public function test_wizard_basic_setup_ext_order_id()
    {
        $this->assertArrayHasKey('extOrderId', $this->transaction->toArray());
        $this->assertNotEmpty($this->transaction->toArray()['extOrderId']);
    }

    public function test_wizard_basic_setup_notify_url()
    {
        $this->assertArrayHasKey('notifyUrl', $this->transaction->toArray());
        $this->assertNotEmpty($this->transaction->toArray()['notifyUrl']);
    }

    public function test_notify_url_generator()
    {
        $this->assertEquals(
            route(config('payu.routing.notification.route_name'), $this->transaction->toArray()['extOrderId']),
            $this->transaction->toArray()['notifyUrl']
        );
    }

    public function test_multiple_products_add()
    {
        $this
            ->transaction
            ->addProducts(self::getProducts());

        $this->assertCount(3, $this->transaction->toArray()['products']);
    }

    public function test_single_product_add()
    {
        $this
            ->transaction
            ->addProducts(self::getProducts())
            ->addProduct(Product::make('Product 4', 800, .25));

        $this->assertCount(4, $this->transaction->toArray()['products']);
    }

    public function test_buyer_definition()
    {
        $buyer = $this->getBuyer()->toArray();

        $this->assertIsArray($buyer);
        $this->assertEquals(199, $buyer['extCustomerId']);
        $this->assertEquals('example@example.com', $buyer['email']);
        $this->assertEquals('987567192', $buyer['phone']);
        $this->assertEquals('John', $buyer['firstName']);
        $this->assertEquals('Travolta', $buyer['lastName']);
        $this->assertEquals('en', $buyer['language']);
    }

    public function test_address_delivery_definition()
    {
        $delivery = $this->getAddressDelivery()->toArray();

        $this->assertEquals('test@example.com', $delivery['recipientEmail']);
        $this->assertEquals('Jonathan Kovalsky', $delivery['recipientName']);
        $this->assertEquals('198765432', $delivery['recipientPhone']);
        $this->assertEquals('Krakow', $delivery['city']);
        $this->assertEquals('Zakopianska 200/2', $delivery['street']);
        $this->assertEquals('91-200', $delivery['postalCode']);
        $this->assertEquals('PL', $delivery['countryCode']);
    }

    public function test_address_postal_box_definition()
    {
        $delivery = $this->getPostalBoxDelivery()->toArray();

        $this->assertEquals('test2@example.com', $delivery['recipientEmail']);
        $this->assertEquals('Michael Novak', $delivery['recipientName']);
        $this->assertEquals('600500200', $delivery['recipientPhone']);
        $this->assertEquals('WA101', $delivery['postalBox']);
    }

    public function test_guess_buyer_data_when_address_delivery_set_only(): void
    {
        $this->transaction->setDelivery(self::getAddressDelivery());

        $this->assertEquals(
            $this->transaction->toArray()['buyer']['email'],
            $this->transaction->toArray()['buyer']['delivery']['recipientEmail']
        );
        $this->assertEquals(
            'test@example.com',
            $this->transaction->toArray()['buyer']['delivery']['recipientEmail']
        );


        $this->assertEquals(
            $this->transaction->toArray()['buyer']['phone'],
            $this->transaction->toArray()['buyer']['delivery']['recipientPhone']
        );
        $this->assertEquals(
            '198765432',
            $this->transaction->toArray()['buyer']['delivery']['recipientPhone']
        );


        $this->assertStringContainsString(
            $this->transaction->toArray()['buyer']['firstName'],
            $this->transaction->toArray()['buyer']['delivery']['recipientName']
        );
        $this->assertStringContainsString(
            'Jonathan',
            $this->transaction->toArray()['buyer']['delivery']['recipientName']
        );


        $this->assertStringContainsString(
            $this->transaction->toArray()['buyer']['lastName'],
            $this->transaction->toArray()['buyer']['delivery']['recipientName']
        );
        $this->assertStringContainsString(
            'Kovalsky',
            $this->transaction->toArray()['buyer']['delivery']['recipientName']
        );

    }

    public function test_guess_buyer_data_when_postal_box_delivery_set_only(): void
    {
        $this->transaction->setDelivery(self::getPostalBoxDelivery());

        $this->assertEquals(
            $this->transaction->toArray()['buyer']['email'],
            $this->transaction->toArray()['buyer']['delivery']['recipientEmail']
        );
        $this->assertEquals(
            'test2@example.com',
            $this->transaction->toArray()['buyer']['delivery']['recipientEmail']
        );


        $this->assertEquals(
            $this->transaction->toArray()['buyer']['phone'],
            $this->transaction->toArray()['buyer']['delivery']['recipientPhone']
        );
        $this->assertEquals(
            '600500200',
            $this->transaction->toArray()['buyer']['delivery']['recipientPhone']
        );



        $this->assertStringContainsString(
            $this->transaction->toArray()['buyer']['firstName'],
            $this->transaction->toArray()['buyer']['delivery']['recipientName']
        );
        $this->assertStringContainsString(
            'Michael',
            $this->transaction->toArray()['buyer']['delivery']['recipientName']
        );

        $this->assertStringContainsString(
            $this->transaction->toArray()['buyer']['lastName'],
            $this->transaction->toArray()['buyer']['delivery']['recipientName']
        );
        $this->assertStringContainsString(
            'Novak',
            $this->transaction->toArray()['buyer']['delivery']['recipientName']
        );
    }


}


