<?php

namespace xGrz\PayU\Facades\TransactionWizard;


use xGrz\PayU\Api\Exceptions\PayUGeneralException;

class Products
{
    private array $data = [];

    /**
     * @throws PayUGeneralException
     */
    public function __construct(array $products = [])
    {
        foreach ($products as $product) {
            if ($product instanceof Product) {
                $this->data[] = $product;
            } else {
                throw new PayUGeneralException('Product must be an instance of ' . Product::class);
            }
        }
    }

    public function add(Product|array $product): static
    {
        if ($product instanceof Product) {
            $this->data[] = $product;
        } else {
            foreach ($product as $p) {
                $this->data[] = $p;
            }
        }

        return $this;
    }

    public function getProducts(): array
    {
        return $this->data;
    }

    public function countTotalAmount(): int
    {
        $amount = 0;
        foreach ($this->data as $product) {
            $amount += $product->getValue();
        }
        return $amount;
    }

    public function toArray(): array
    {
        $output = [];
        foreach ($this->data as $product) {
            $output[] = $product->toArray();
        }
        return $output;
    }

    /**
     * @throws PayUGeneralException
     */
    public static function make(array $products):static
    {
        return new static($products);
    }


}
