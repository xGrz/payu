<?php

return [
    'use_sandbox' => true,

    'job_delay' => [
        'refund' => [
            'send' => 30,
            'retry' => 3
        ],
        'payout' => [
            'send' => 30,
            'retry' => 3
        ],
        'transaction_method_check' => 10
    ],
    'interval' => [
        'payout_status_check' => 60,
    ],

    'routing' => [
        'notification' => [
            'route_name' => 'payu.notification',
            'endpoint_name' => 'payu-payment-notification'
        ],
    ],

    'expose_admin_panel' => [
        'expose' => true,
        'prefix' => 'payu',
        'url' => 'payu',
        'paymentController' => xGrz\PayU\Http\Controllers\PaymentController::class,
        'refundController' => xGrz\PayU\Http\Controllers\RefundController::class,
        'payoutController' => xGrz\PayU\Http\Controllers\PayoutController::class,
    ],

    // Do not pass any credential here. Please use your .env file to add keys and values
    'api' => [
        'shopId' => env('PAYU_SHOP_ID', null),
        'merchantPosId' => env('PAYU_MERCHANT_POS_ID', null),
        'signatureKey' => env('PAYU_SIGNATURE_KEY', null),
        'oAuthClientId' => env('PAYU_O_AUTH_CLIENT_ID', null),
        'oAuthClientSecret' => env('PAYU_O_AUTH_CLIENT_SECRET', null),
    ]
];

