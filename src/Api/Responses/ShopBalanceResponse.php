<?php

namespace xGrz\PayU\Api\Responses;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Number;
use xGrz\PayU\Api\BaseApiResponse;

class ShopBalanceResponse extends BaseApiResponse
{
    protected array $data = [
        'name' => null,
        'description' => null,
        'currency_code' => null,
        'amount' => [
            'balance' => 0,
            'available' => 0,
            'reserved' => 0
        ],
        'humanAmount' => [
            'balance' => 0,
            'available' => 0,
            'reserved' => 0
        ]
    ];

    protected function __construct(Response $response)
    {
        $this
            ->setName($response->json('name'))
            ->setDescription($response->json('description'))
            ->setCurrency($response->json('balance.currencyCode'))
            ->setBalance($response->json('balance.total'))
            ->setAvailable($response->json('balance.available'))
            ->countReserve();
    }

    private function setName(string $shopName): static
    {
        $this->data['name'] = $shopName;
        return $this;
    }

    private function setDescription(?string $description): static
    {
        $this->data['description'] = $description ?? '';
        return $this;
    }

    private function setCurrency(string $currencyCode): static
    {
        $this->data['currencyCode'] = $currencyCode;
        return $this;
    }

    private function setBalance(int $balance): static
    {
        $balance = $balance / 100;
        $this->data['amount']['balance'] = $balance;
        $this->data['humanAmount']['balance'] = self::toHumanReadable($balance);
        return $this;
    }

    private function setAvailable(int $availableAmount): static
    {
        $availableAmount = $availableAmount / 100;
        $this->data['amount']['available'] = $availableAmount;
        $this->data['humanAmount']['available'] = self::toHumanReadable($availableAmount);
        return $this;
    }

    private function countReserve(): static
    {
        $this->data['amount']['reserved'] = $this->data['amount']['balance'] - $this->data['amount']['available'];
        $this->data['humanAmount']['reserved'] = self::toHumanReadable($this->data['amount']['reserved']);
        return $this;
    }

    private function toHumanReadable(int|float $amount): string
    {
        return Number::currency($amount, $this->data['currencyCode'], app()->currentLocale());
    }

}
