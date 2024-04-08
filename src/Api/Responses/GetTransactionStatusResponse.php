<?php

namespace xGrz\PayU\Api\Responses;

use Illuminate\Http\Client\Response;
use xGrz\PayU\Api\BaseApiResponse;
use xGrz\PayU\Enums\PaymentStatus;

class GetTransactionStatusResponse extends BaseApiResponse
{
    protected array $data = [
        'status' => null,
    ];

    protected function __construct(Response $response)
    {
        $this->data['status'] = $response->json('orders.0.status');
    }

    public function getStatus(): PaymentStatus
    {
        return PaymentStatus::findByName($this->data['status']);
    }

}
