<?php

use xGrz\PayU\Enums\PaymentStatus;

return [
    'created' => 'Transakcja została utworzona',
    'accept' => [
        'success' => 'Płatność została zaakceptowana.',
        'failed' => 'Wystąpił błąd podczas akceptowania transakcji.',
    ],
    'reject' => [
        'success' => 'Płatność została odrzucona.',
        'failed' => 'Wystąpił błąd podczas odrzucania transakcji.',
    ],
    'status' => [
        PaymentStatus::INITIALIZED->name => 'Zainicjowana',
        PaymentStatus::NEW->name => 'Utworzona',
        PaymentStatus::PENDING->name => 'Rozpoczęta',
        PaymentStatus::WAITING_FOR_CONFIRMATION->name => 'Czeka na akceptację',
        PaymentStatus::COMPLETED->name => 'Opłacona',
        PaymentStatus::CANCELED->name => 'Anulowana'
    ]
];
