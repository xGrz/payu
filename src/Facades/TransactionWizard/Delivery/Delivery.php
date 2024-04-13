<?php

namespace xGrz\PayU\Facades\TransactionWizard\Delivery;

use xGrz\PayU\Facades\TransactionWizard\Buyer;
use xGrz\PayU\Interfaces\DeliveryTypeInterface;
use xGrz\PayU\Traits\Arrayable;

abstract class Delivery implements DeliveryTypeInterface
{
    use Arrayable;

    protected array $data = [];

    private array $commonDeliveryData = [
        'recipientName' => null,
        'recipientEmail' => null,
        'recipientPhone' => null
    ];

    public function __construct()
    {
        $this->data = [
            ...$this->commonDeliveryData,
            ...$this->data,

        ];
    }

    public function getBuyer(): Buyer
    {
        return Buyer::make(
            $this->data['recipientEmail'],
            $this->data['recipientPhone'],
            self::guessBuyerName()['firstName'],
            self::guessBuyerName()['lastName'],
            auth()->id(),
            app()->getLocale(),
        );
    }

    private function guessBuyerName(): array
    {
        $names = explode(' ', $this->data['recipientName']);

        return (count($names) === 2)
            ? ['firstName' => $names[0], 'lastName' => $names[1]]
            : ['firstName' => $this->data['recipientName'], 'lastName' => $this->data['recipientName']];
    }

    public function __set($key, $value)
    {
        if (array_key_exists($key, $this->data)) $this->data[$key] = $value;
    }

    public function __call(string $name, $arguments): DeliveryTypeInterface
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
}
