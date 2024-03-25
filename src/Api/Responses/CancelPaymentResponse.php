<?php

namespace xGrz\PayU\Api\Responses;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use xGrz\PayU\Api\BaseApiResponse;

class CancelPaymentResponse extends BaseApiResponse
{
    protected array $data = [
        'status' => '',
        'message' => '',
        'ext_order_id' => null,
    ];

    protected function __construct(Response $response)
    {
        $this->data['status'] = $response->json('status.statusCode');
        $this->data['message'] = $response->json('status.statusDesc');
        $this->data['ext_order_id'] = $response->json('extOrderId');
        Log::withContext([
            'userId' => Auth::id(),
            'status' => $response->json('status.statusCode'),
            'message' => $response->json('status.statusDesc'),
            'transaction_id' => $this->data['ext_order_id']
        ])
            ->info(self::isCanceled() ? 'PayU Success | Transaction revoked' : 'PayU Failed | Transaction not revoked.');
    }

    public function isCanceled(): bool
    {
        return $this->data['status'] === 'SUCCESS';
    }

    public function getMessage(): string
    {
        return $this->data['message'];
    }


}
