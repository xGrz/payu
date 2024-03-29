<?php

namespace xGrz\PayU\Api\Responses;

use Illuminate\Http\Client\Response;
use xGrz\PayU\Api\BaseApiResponse;
use xGrz\PayU\Traits\Arrayable;

class CreatePaymentResult extends BaseApiResponse
{

    use Arrayable;

    protected array $data = [
        'link' => null,
        'payu_order_id' => null,
    ];

    protected function __construct(Response $response)
    {
        $this->data['link'] = $response->json('redirectUri');
        $this->data['payu_order_id'] = $response->json('orderId');
    }

}
