<?php

return [
    'routing' => [
        'expose_web_panel' => false,
        'web' => [
            'group_name' => 'payu',
            'uri_prefix' => 'payu',
        ],
        'controllers' => [
            'payment' => xGrz\PayU\Http\Controllers\PaymentController::class,
            'refund' => xGrz\PayU\Http\Controllers\RefundController::class,
            'payout' => xGrz\PayU\Http\Controllers\PayoutController::class,
            'methods' => xGrz\PayU\Http\Controllers\MethodsController::class,
        ]
    ],
];
