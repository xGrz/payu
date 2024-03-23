<?php

namespace xGrz\PayU\Facades\TransactionWizard;

use xGrz\PayU\Traits\Arrayable;

/**
 * @method setName(string|null $name)
 * @method setQuantity(float|int $quantity)
 * @method setVirtual(bool $true)
 */
class Product
{
    use Arrayable;
    private array $data = [
        'name' => null,
        'unitPrice' => null,
        'quantity' => null,
        'virtual' => null
    ];

    public function __construct(string $name = null, int|float $unitPrice = null, int|float $quantity = 1, bool $isVirtual = false)
    {
        $this
            ->setQuantity($quantity)
            ->setUnitPrice($unitPrice)
            ->setName($name);
        if ($isVirtual) $this->setVirtual(true);

    }

    public function __call(string $name, $arguments): static
    {
        if (str($name)->startsWith('set')) {
            $key = (string)str($name)->replaceStart('set', '')->camel();
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = $arguments[0];
                return $this;
            }
        }
        throw new \TypeError('Method ' . $name . ' not found');
    }

    public function setUnitPrice(int|float $unitPrice): static
    {
        $this->data['unitPrice'] = (int) $unitPrice * 100;
        return $this;
    }

    public function getValue(): int
    {
        return $this->data['quantity'] * $this->data['unitPrice'];
    }

    public static function make(string $name = null, int|float $unitPrice = null, int|float $quantity = 1, bool $isVirtual = false): static
    {
        return new static($name, $unitPrice, $quantity, $isVirtual);
    }

}
