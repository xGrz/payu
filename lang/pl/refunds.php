<?php

use xGrz\PayU\Enums\RefundStatus;

return [
    'create' => [
        'success' => 'Polecenie zwrotu środków zostało utworzone.',
        'failed' => 'Nie udało się utworzyć polecenia zwrotu środków.',
    ],
    'retry' => [
        'success' => 'Zlecono ponowną próbę zwrotu środków.',
        'failed' => 'Wystąpił błąd podczas próby ponownego zwrotu środków.',
    ],
    'destroy' => [
        'success' => 'Dyspozycja zwrotu środków została usunięta.',
        'failed' => 'Nie udało się usunąć dyspozycji zwrotu środków.'
    ],
    'status' => [
        RefundStatus::INITIALIZED->name => 'Zainicjowany',
        RefundStatus::SENT->name => 'Wysłany',
        RefundStatus::PENDING->name => 'Oczekuje',
        RefundStatus::CANCELED->name => 'Anulowany',
        RefundStatus::FINALIZED->name => 'Zakończony',
        RefundStatus::ERROR->name => 'Błąd',
        RefundStatus::SCHEDULED->name => 'Zaplanowany',
        RefundStatus::RETRY->name => 'Ponawiam',
    ],
    'errors' => [
        'AMOUNT_TO_BIG' => 'Kwota zbyt wysoka'
    ]
];
