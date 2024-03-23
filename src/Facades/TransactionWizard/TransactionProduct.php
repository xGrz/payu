<?php

namespace xGrz\PayU\Facades\TransactionWizard;

class TransactionProduct
{

    public readonly string $name;
    public readonly int $unitPrice;
    public readonly int|float $quantity;

    public readonly bool $virtual;

    public function __construct(string $name = null, int|float $unitPrice = null, int|float $quantity = 1, bool $isVirtual = false)
    {
        if ($name) $this->setProductName($name);
        if ($unitPrice) $this->setPrice(round($unitPrice * 100));
        if ($quantity) $this->setQuantity($quantity);
        if ($isVirtual) $this->setVirtualProduct($isVirtual);
    }

    public function setProductName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function setPrice(int $unitPrice): static
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    public function setQuantity(float|int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setVirtualProduct(bool $isVirtual = false): static
    {
        $this->virtual = $isVirtual;
        return $this;
    }

    public function get(): array
    {
        return get_object_vars($this);
    }

    public function value(): int
    {
        return $this->unitPrice * $this->quantity;
    }

    public static function create(string $name = null, int|float $unitPrice = null, int|float $quantity = 1, bool $isVirtual = false): TransactionProduct
    {
        return new self($name, $unitPrice, $quantity, $isVirtual);
    }

}

