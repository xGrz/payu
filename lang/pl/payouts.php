<?php

use xGrz\PayU\Enums\PayoutStatus;

return [
    'create' => [
        'success' => 'Polecenie wypłaty zostało utworzone.',
        'failed' => 'Polecenie wypłaty nie zostało utworzone.',
    ],
    'updateStatus' => [
        'success' => 'Aktualizacja statusu w toku.',
    ],
    'retry' => [
        'success' => 'Ponawiam próbę zlecenia wypłaty.',
        'failed' => 'Nie udało się ponowić próby zlecenia wypłaty',
    ],
    'destroy' => [
        'success' => 'Zlecenie wypłaty zostało anulowane.',
        'failed' => 'Nie udało się anulować zlecenia wypłaty.'
    ],
    'status' => [
        PayoutStatus::INIT->name => 'Zainicjowana',
        PayoutStatus::PENDING->name => 'W kolejce',
        PayoutStatus::WAITING->name => 'Czeka na realizację',
        PayoutStatus::CANCELED->name => 'Anulowana',
        PayoutStatus::REALIZED->name => 'Zrealizowana',
        PayoutStatus::SCHEDULED->name => 'Zaplanowana',
        PayoutStatus::RETRY->name => 'Ponawiam'
    ],
];
