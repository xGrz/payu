<?php


return [
    'use_sandbox' => true,

    'delay' => [
        'refund' => 180,
        'payout' => 180
    ],

    'routing' => [
        'notification' => [
            'route_name' => 'payu.notification',
            'endpoint_name' => 'payu-payment-notification'
        ],
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

