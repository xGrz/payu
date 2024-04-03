<?php

namespace xGrz\PayU\Api\Responses;


use Illuminate\Http\Client\Response;
use xGrz\PayU\Api\BaseApiResponse;
use xGrz\PayU\Enums\PayoutStatus;

class CreatePayoutResponse extends BaseApiResponse
{

    protected array $data = [
        'payout_id' => null,
        'status' => null,
        'amount' => 0
    ];

    protected function __construct(Response $response)
    {
        $this->data['payout_id'] = $response->json('payout.payoutId');
        $this->data['status'] = PayoutStatus::findByName($response->json('payout.status'));
    }

    public function setAmount(int $amountInCents): static
    {
        $this->data['amount'] = $amountInCents / 100;
        return $this;
    }

}
