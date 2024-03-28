<?php

namespace xGrz\PayU\Api\Responses;

use Illuminate\Http\Client\Response;
use xGrz\PayU\Api\BaseApiResponse;

class GetPaymentMethodsResponse extends BaseApiResponse
{

    protected function __construct(Response $response)
    {
        foreach ($response->json('payByLinks') as $method) {
            $this->data[] = [
                'image' => $method['brandImageUrl'],
                'name' => $method['name'],
                'available' => $method['status'] === 'ENABLED',
                'code' => $method['value'],
                'type' => 'PBL',
                'min_amount' => $method['minAmount'] ?? 0,
                'max_amount' => $method['maxAmount'] ?? 99999999,
            ];
        }
    }


}
