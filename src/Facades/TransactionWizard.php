<?php

namespace xGrz\PayU\Facades;

use Illuminate\Support\Str;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Facades\TransactionWizard\Buyer;
use xGrz\PayU\Facades\TransactionWizard\Delivery\PostalBox;
use xGrz\PayU\Facades\TransactionWizard\PayMethod;
use xGrz\PayU\Facades\TransactionWizard\Product;
use xGrz\PayU\Facades\TransactionWizard\Products;
use xGrz\PayU\Interfaces\DeliveryTypeInterface;

class TransactionWizard
{
    private ?Products $products = null;
    private ?Buyer $buyer = null;
    private ?DeliveryTypeInterface $delivery = null;

    private ?PayMethod $method = null;
    private array $data = [
        /* REQUIRED */
        'customerIp' => '',
        'merchantPosId' => '',
        'description' => '',
        'currencyCode' => 'PLN',
        'totalAmount' => 0,
        'products' => [],
    ];

    public function __construct(string $description = null, ?Products $products = null, ?Buyer $buyer = null, ?DeliveryTypeInterface $delivery = null, string $redirectAfterTransaction = null, ?PayMethod $method = null)
    {
        self::fillTransaction();
        if (!empty($description)) self::setDescription($description);
        if (!empty($redirectAfterTransaction)) self::setRedirectAfterTransaction($redirectAfterTransaction);
        $this->products = $products ?? new Products();
        if (!empty($buyer)) self::setBuyer($buyer);
        if (!empty($delivery)) self::setDelivery($delivery);
        if (!empty($method)) self::setMethod($method);
    }

    public function setDescription(string $description): static
    {
        $this->data['description'] = $description;
        if (empty($this->data['visibleDescription'])) $this->data['visibleDescription'] = $description;
        return $this;
    }

    public function setVisibleDescription(string $description): static
    {
        $this->data['visibleDescription'] = $description;
        return $this;
    }

    public function addProduct(Product $product): static
    {
        $this->products->add($product);
        return $this;
    }

    public function addProducts(Products $products): static
    {
        foreach ($products->getProducts() as $product) {
            $this->addProduct($product);
        }
        return $this;
    }

    public function setBuyer(Buyer $buyer): static
    {
        $this->buyer = $buyer;
        return $this;
    }

    public function setMethod(PayMethod $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function setDelivery(DeliveryTypeInterface $delivery): static
    {
        $this->delivery = $delivery;
        if (empty($this->buyer)) $this->buyer = $this->delivery->getBuyer();
        return $this;
    }

    public function setCustomerIp(?string $customerIpAddress = null): static
    {
        $this->data['customerIp'] = $customerIpAddress ?? request()->ip();
        return $this;
    }

    public function setRedirectAfterTransaction(string $url): static
    {
        $this->data['continueUrl'] = $url;
        return $this;
    }

    public function toArray(): array
    {
        $transaction = $this->data;
        $transaction['products'] = $this->products->toArray();
        $transaction['totalAmount'] = $this->products->countTotalAmount();

        if (!empty($this->buyer)) $transaction['buyer'] = $this->buyer->toArray();
        if (!empty($this->delivery) && !empty($this->buyer)) $transaction['buyer']['delivery'] = $this->delivery->toArray();
        if (!empty($this->method)) $transaction['payMethods']['payMethod'] = $this->method->toArray();
        return $transaction;
    }

    public static function make(string $description = null, ?Products $products = null, ?Buyer $buyer = null, ?DeliveryTypeInterface $delivery = null, string $redirectAfterTransaction = null, ?PayMethod $method = null): static
    {
        return new static($description, $products, $buyer, $delivery, $redirectAfterTransaction, $method);
    }

    /**
     * Fake transaction for sandbox purposes only
     * @param string|null $redirectAfterTransaction
     * @throws PayUGeneralException
     */
    public static function fake(string $redirectAfterTransaction = null): static
    {
        return new static(
            'Order no ' . rand(1, 5000) . '/' . date('Y'),
            Products::make([
                Product::make('Product A', rand(1, 300), rand(1, 3)),
                Product::make('Product B', rand(1, 300), rand(1, 2)),
                Product::make('Product C', rand(1, 300), rand(2, 3)),
            ]),
            Buyer::make(auth()->user()->email ?? 'test@example.com', '909765456', 'John', 'Kovalsky'),
            PostalBox::make(auth()->user()->email ?? 'test@example.com', 'John Kovalsky', '909765456', 'WA101'),
            $redirectAfterTransaction ?? route('home'),
            Config::hasPayMethods()
                ? PayMethod::make('P')
                : null
        );
    }

    private function fillTransaction(): void
    {
        $this->setCustomerIp();
        $this->buildOrderId();
        $this->setMerchantPosId();
    }

    private function setNotifyUrl(): void
    {
        $this->data['notifyUrl'] = route(
            config('payu.routing.notification.route_name', 'payu.notification'),
            $this->data['extOrderId']
        );
    }

    private function setMerchantPosId(): void
    {
        $this->data['merchantPosId'] = Config::getMerchantPosId();
    }

    private function buildOrderId(string|int $uuid = null): void
    {
        $this->data['extOrderId'] = $uuid ?? (string)Str::orderedUuid();
        $this->setNotifyUrl();
    }


}
