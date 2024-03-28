<?php

namespace xGrz\PayU\Api\Responses;

use Illuminate\Http\Client\Response;
use xGrz\PayU\Api\BaseApiResponse;
use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Exceptions\StatusNameException;

class SendRefundRequestResponse extends BaseApiResponse
{
    protected array $data = [
        'payu_order_id' => null,
        'refund_id' => null,
        'ext_refund_id' => null,
        'amount' => null,
        'currency_code' => null,
        'description' => null,
        'bank_description' => null,
        'status' => null,
    ];

    protected function __construct(Response $response)
    {
        $this->data['payu_order_id'] = $response->json('orderId');
        $this->data['refund_id'] = $response->json('refund.refundId');
        $this->data['ext_refund_id'] = $response->json('refund.extRefundId');
        $this->data['amount'] = $response->json('refund.amount');
        $this->data['currency_code'] = $response->json('refund.currencyCode');
        $this->data['status'] = $response->json('refund.status');
        $this->data['description'] = $response->json('refund.description') ?? '';
        $this->data['bank_description'] = $response->json('refund.bank_description') ?? '';

    }

    /**
     * @throws StatusNameException
     */
    public function getStatus(): PayoutStatus
    {
        return PayoutStatus::findByName($this->status);
    }

}
