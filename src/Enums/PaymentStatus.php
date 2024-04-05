<?php

namespace xGrz\PayU\Enums;

use xGrz\PayU\Interfaces\WithActions;
use xGrz\PayU\Interfaces\WithColors;
use xGrz\PayU\Interfaces\WithLabel;
use xGrz\PayU\Traits\HasActions;
use xGrz\PayU\Traits\HasLabel;
use xGrz\PayU\Traits\WithNames;

enum PaymentStatus: int implements WithActions, WithColors, WithLabel
{

    use WithNames, HasLabel, HasActions;

    case INITIALIZED = 0;
    case NEW = 1;
    case PENDING = 2;
    case WAITING_FOR_CONFIRMATION = 3;
    case COMPLETED = 4;
    case CANCELED = 5;


    public function actions(): array
    {
        return match ($this) {
            self::INITIALIZED => ['pay', 'delete', 'processing'],
            self::PENDING => ['pay', 'delete', 'processing', 'reset'],
            self::WAITING_FOR_CONFIRMATION => ['accept', 'reject', 'processing'],
            self::COMPLETED => ['refund', 'paymethod', 'success'],
            self::CANCELED => ['failed'],
            default => []
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::INITIALIZED, self::NEW => 'gray',
            self::PENDING => 'info',
            self::WAITING_FOR_CONFIRMATION => 'warning',
            self::COMPLETED => 'success',
            self::CANCELED => 'danger',
            default => ''
        };
    }

    public function getLangKey(): string
    {
        return 'transactions.status';
    }

}
