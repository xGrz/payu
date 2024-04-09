<?php


use Tests\TestCase;
use xGrz\PayU\Facades\TransactionWizard\Product;
use xGrz\PayU\Facades\TransactionWizard\Products;

class TransactionProductsTest extends TestCase
{

    private Product $product;


    public function setUp(): void
    {
        parent::setUp();
        $this->product = new Product('ProductName', 100, 2);
    }

    public function test_product_has_attribute_name(): void
    {
        $this->assertArrayHasKey('name', $this->product->toArray());
        $this->assertEquals('ProductName', $this->product->toArray()['name']);
    }

    public function test_product__attribute_unit_price(): void
    {
        $this->assertArrayHasKey('unitPrice', $this->product->toArray());
        $this->assertEquals(10000, $this->product->toArray()['unitPrice']);

    }

    public function test_product_attribute_quantity(): void
    {
        $this->assertArrayHasKey('quantity', $this->product->toArray());
        $this->assertEquals(2, $this->product->toArray()['quantity']);
    }

    public function test_product_value_count(): void
    {
        $this->assertEquals(20000, $this->product->getValue());
    }

    public function test_product_set_name()
    {
        $this->product->setName('ProductNameSet');
        $this->assertEquals('ProductNameSet', $this->product->toArray()['name']);
    }

    public function test_product_set_unit_price()
    {
        $this->product->setUnitPrice(10);
        $this->assertEquals(1000, $this->product->toArray()['unitPrice']);

        $this->product->setUnitPrice(200);
        $this->assertEquals(20000, $this->product->toArray()['unitPrice']);
    }

    public function test_product_set_unit_price_with_tens()
    {
        $this->product->setUnitPrice(10.29);
        $this->assertEquals(1029, $this->product->toArray()['unitPrice']);
    }

    public function test_product_set_unit_price_with_decimals()
    {
        $this->product->setUnitPrice(10.2999999);
        $this->assertEquals(1030, $this->product->toArray()['unitPrice']);


        $this->product->setUnitPrice(3.33333333);
        $this->assertEquals(333, $this->product->toArray()['unitPrice']);


        $this->product->setUnitPrice(66.66666);
        $this->assertEquals(6667, $this->product->toArray()['unitPrice']);

    }

    public function test_make_static_product()
    {
        $p = Product::make('Product 1', 100, 2);
        $this->assertEquals('Product 1', $p->toArray()['name']);
        $this->assertEquals(10000, $p->toArray()['unitPrice']);
        $this->assertEquals(2, $p->toArray()['quantity']);
        $this->assertEquals(20000, $p->getValue());
    }

    public function test_add_products_to_container_object()
    {
        $products = new Products();
        $products->add(Product::make('Product 1', 100, 2));
        $products->add(Product::make('Product 2', 200, 1));
        $products->add(Product::make('Product 3', 400, .5));

        $this->assertCount(3, $products->toArray());
        $this->assertCount(3, $products->getProducts());
    }

    public function test_add_products_to_container_in_constructor()
    {
        $products = new Products([
            Product::make('Product 1', 100, 2),
            Product::make('Product 2', 200, 1),
            Product::make('Product 3', 400, .5),
        ]);

        $this->assertCount(3, $products->toArray());
        $this->assertCount(3, $products->getProducts());
    }

    public function test_count_total_amount_of_products_in_container(): void
    {
        $products = new Products([
            Product::make('Product 1', 100, 2),
            Product::make('Product 2', 200, 1),
            Product::make('Product 3', 400, .5),
        ]);

        $this->assertEquals(60000, $products->countTotalAmount());
    }

}
