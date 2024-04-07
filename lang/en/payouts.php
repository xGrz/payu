<?php

use xGrz\PayU\Enums\PayoutStatus;

return [
    'create' => [
        'success' => 'Payout has been successfully scheduled.',
        'failed' => 'Payout not initialed. Error occurred.',
    ],
    'updateStatus' => [
        'success' => 'Payout status check initialized.',
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
        PayoutStatus::INIT->name => 'Initialized',
        PayoutStatus::PENDING->name => 'Pending',
        PayoutStatus::WAITING->name => 'Waiting',
        PayoutStatus::CANCELED->name => 'Canceled',
        PayoutStatus::REALIZED->name => 'Done',
        PayoutStatus::SCHEDULED->name => 'Scheduled',
        PayoutStatus::RETRY->name => 'Retrying'
    ],
    'errors' => [
        'NOT_ENOUGH_FUNDS' => 'Not enough funds',
    ],
];
