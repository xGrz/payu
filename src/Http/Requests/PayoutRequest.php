<?php

namespace xGrz\PayU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayoutRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'payoutAmount' => ['numeric', 'required']
        ];
    }
}
