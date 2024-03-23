<?php

namespace xGrz\PayU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isOrderNotification = (bool) $this->order;
        $isRefundNotification = (bool) $this->refund;

        return [
            'order' => ['array', 'sometimes'],
            'order.extOrderId' => ['string', Rule::requiredIf($isOrderNotification)],
            'order.status' => ['string', Rule::requiredIf($isOrderNotification)],
            'order.products' => ['array', Rule::requiredIf($isOrderNotification)],
            'order.products.*.name' => ['string', Rule::requiredIf($isOrderNotification)],
            'order.products.*.unitPrice' => ['integer', Rule::requiredIf($isOrderNotification)],
            'order.products.*.quantity' => ['numeric', Rule::requiredIf($isOrderNotification)],
            'order.orderId' => ['string', Rule::requiredIf($isOrderNotification)],

            'refund' => ['array', 'sometimes'],
            'refund.refundId' => ['string', Rule::requiredIf($isRefundNotification)],
            'refund.extRefundId' => ['string', Rule::requiredIf($isRefundNotification)],
            'refund.amount' => ['integer', Rule::requiredIf($isRefundNotification)],
            'refund.currencyCode' => ['string', Rule::requiredIf($isRefundNotification)],
            'refund.status' => ['string', Rule::requiredIf($isRefundNotification)],
            'refund.refundDate' => ['string', Rule::requiredIf($isRefundNotification)],
            'refund.reasonDescription' => ['string'],
            'orderId' => ['string', Rule::requiredIf($isRefundNotification)],
            'extOrderId' => ['string', Rule::requiredIf($isRefundNotification)],
        ];
    }
}
