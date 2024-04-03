<?php

namespace xGrz\PayU\Api\Responses;

use Illuminate\Http\Client\Response;
use xGrz\PayU\Api\BaseApiResponse;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Models\Method;

class RetrieveTransactionPayMethodResponse extends BaseApiResponse
{
    private Method $method;

    protected array $data = [
        'code' => null,
    ];

    /**
     * @throws PayUGeneralException
     */
    protected function __construct(Response $response)
    {
        $methodCode = $response->json('transactions.0.payMethod.value');
        if ($methodCode) {
            $this->method = Method::where('code', $methodCode)->first();
            if (!$this->method) throw new PayUGeneralException('Payment method not found for code [' . $methodCode . ']');
            $this->data['code'] = $methodCode;
            return;
        }
        throw new PayUGeneralException('Payment method not received');

    }

    public function getMethod(): ?Method
    {
        return $this->method;
    }
}
