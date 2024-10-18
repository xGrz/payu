<?php

return [
    'jobs' => [
        'delay' => [
            'refund' => [
                'send' => 90,
                'retry' => 60
            ],
            'payout' => [
                'send' => 90,
                'retry' => 60
            ],
            'transaction_method_check' => 60
        ],
        'interval' => [
            'payout_status_check' => 60,
        ],
    ],

    'routing' => [
        'notifications' => [
            'route_name' => 'payu.notification',
            'endpoint_name' => 'payu-payment-notification',
        ],
    ],

    // Do not pass any credential here!
    // Please use your .env file to book keys and values
    'api' => [
        'use_sandbox' => env('PAYU_USE_SANDBOX', false),
        'shopId' => env('PAYU_SHOP_ID', null),
        'merchantPosId' => env('PAYU_MERCHANT_POS_ID', null),
        'signatureKey' => env('PAYU_SIGNATURE_KEY', null),
        'oAuthClientId' => env('PAYU_O_AUTH_CLIENT_ID', null),
        'oAuthClientSecret' => env('PAYU_O_AUTH_CLIENT_SECRET', null),
    ]
];
