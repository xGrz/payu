<?php

namespace xGrz\PayU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'customer' => ['sometimes', 'array'],
            'customer.name' => ['string'],
            'customer.street' => ['string'],
            'customer.house_number' => ['string'],
            'customer.apartment_number' => ['string', 'nullable'],
            'customer.city' => ['string'],
            'customer.postalCode' => ['string'],
            'customer.email' => ['email'],
            'customer.phone' => ['string'],
            'items' => ['array'],
            'items.*.name' => ['string'],
            'items.*.quantity' => ['numeric'],
            'items.*.price' => ['numeric'],
            'method' => ['sometimes', 'string']
        ];
    }
}
