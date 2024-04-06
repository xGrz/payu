<?php

use xGrz\PayU\Enums\PaymentStatus;

return [
    'created' => 'Transaction created',
    'accept' => [
        'success' => 'Payment was successfully accepted.',
        'failed' => 'An error occurred while accepting your transaction',
    ],
    'reject' => [
        'success' => 'Payment was rejected',
        'failed' => 'An error occurred while rejecting your transaction',
    ],
    'status' => [
        PaymentStatus::INITIALIZED->name => 'Initialized',
        PaymentStatus::NEW->name => 'Created',
        PaymentStatus::PENDING->name => 'In progress',
        PaymentStatus::WAITING_FOR_CONFIRMATION->name => 'Waiting for confirmation',
        PaymentStatus::COMPLETED->name => 'Paid',
        PaymentStatus::CANCELED->name => 'Canceled'
    ]
];
