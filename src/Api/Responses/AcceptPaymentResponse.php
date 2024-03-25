<?php

namespace xGrz\PayU\Api\Responses;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use xGrz\PayU\Api\BaseApiResponse;
use xGrz\PayU\Models\Transaction;

class AcceptPaymentResponse extends BaseApiResponse
{

    protected array $data = [
        'accepted' => false
    ];

    protected function __construct(Response $response)
    {
        $this->data['accepted'] = $response->json('status.statusCode') === 'SUCCESS';

        $transaction = Transaction::where('payu_order_id', $response->json('orderId'))->first();

        Log::withContext([
            'userId' => Auth::id(),
            'status' => $response->json('status.statusCode'),
            'message' => $response->json('status.statusDesc'),
            'transaction_id' => $transaction->id
        ])
            ->info(self::isAccepted() ? 'PayU Success | Transaction accepted' : 'PayU Failed | Transaction not approved.');
    }

    public function isAccepted(): bool
    {
        return (bool) $this->data['accepted'];
    }



}
