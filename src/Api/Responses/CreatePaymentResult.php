<?php

namespace xGrz\PayU\Api\Responses;

use Illuminate\Http\Client\Response;
use xGrz\PayU\Api\BaseApiResponse;
use xGrz\PayU\Models\Transaction;

class CreatePaymentResult extends BaseApiResponse
{
    protected array $data = [
        'link' => null,
        'payu_order_id' => null,
        'id' => null,
        'payload' => []
    ];

    protected Transaction $transaction;

    protected function __construct(Response $response)
    {
        $this->data['link'] = $response->json('redirectUri');
        $this->data['payu_order_id'] = $response->json('orderId');
        $this->data['id'] = $response->json('extOrderId');
        $this->transaction = new Transaction();
        $this->transaction->fill($this->data);
    }

    public function setPayload(array $payload): static
    {
        $this->transaction->fill(['payload' => $payload])->save();
        return $this;
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }


}
