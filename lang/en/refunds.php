<?php

use xGrz\PayU\Enums\RefundStatus;

return [
    'create' => [
        'success' => 'Refund created successfully.',
        'failed' => 'Refund not created. Error occurred.',
    ],
    'retry' => [
        'success' => 'Retrying to send the transaction again.',
        'failed' => 'Retrying to send the transaction failed.',
    ],
    'destroy' => [
        'success' => 'Payout has been deleted.',
        'failed' => 'Error! Payout request cannot be deleted.'
    ],
    'status' => [
        RefundStatus::INITIALIZED->name => 'Initialized',
        RefundStatus::SENT->name => 'Sent',
        RefundStatus::PENDING->name => 'Pending',
        RefundStatus::CANCELED->name => 'Canceled',
        RefundStatus::FINALIZED->name => 'Completed',
        RefundStatus::ERROR->name => 'Error',
        RefundStatus::SCHEDULED->name => 'Scheduled',
        RefundStatus::RETRY->name => 'Retrying',
    ]
];
