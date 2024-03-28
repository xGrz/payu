<?php

namespace xGrz\PayU\Api\Responses;


use Illuminate\Http\Client\Response;
use xGrz\PayU\Api\BaseApiResponse;
use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Exceptions\StatusNameException;

class GetPayoutStatusResponse extends BaseApiResponse
{
    protected array $data = [
        'payout_id' => null,
        'status' => null,
        'amount' => null,
    ];


    protected function __construct(Response $response)
    {
        $this->data['payout_id'] = $response->json('payout.payoutId');
        $this->data['status'] = $response->json('payout.status');
        $this->data['amount'] = $response->json('payout.amount') / 100;
    }

    /**
     * @throws StatusNameException
     */
    public function getStatus(): PayoutStatus
    {
        return PayoutStatus::findByName($this->data['status']);
    }

}
