<?php

namespace xGrz\PayU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['numeric', 'required'],
            'description' => ['string', 'required'],
            'bankDescription' => ['string', 'nullable']
        ];
    }
}
