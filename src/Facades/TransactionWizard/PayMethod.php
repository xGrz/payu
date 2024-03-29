<?php

namespace xGrz\PayU\Facades\TransactionWizard;

use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Models\Method;
use xGrz\PayU\Traits\Arrayable;

class PayMethod
{
    use Arrayable;

    private array $data = [
        'type' => '',
        'value' => '',
    ];

    public function __construct(Method|string $method)
    {
        if (is_string($method)) $method = Method::find($method);
        if (!$method) throw new PayUGeneralException('Method not found');
        $this->data['type'] = $method->type;
        $this->data['value'] = $method->code;
    }

    public static function make(Method|string $method): static
    {
        return new static($method);
    }
}
