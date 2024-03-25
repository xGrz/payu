<?php

namespace xGrz\PayU\Enums;

use xGrz\PayU\Interfaces\WithActions;
use xGrz\PayU\Interfaces\WithColors;
use xGrz\PayU\Traits\WithActionsDetect;
use xGrz\PayU\Traits\WithLabels;
use xGrz\PayU\Traits\WithStatusNames;

enum PaymentStatus: int implements WithActions, WithColors
{

    use WithStatusNames, WithLabels, WithActionsDetect;

    case INITIALIZED = 0;
    case NEW = 1;
    case PENDING = 2;
    case WAITING_FOR_CONFIRMATION = 3;
    case COMPLETED = 4;
    case CANCELED = 5;


    public function actions(): array
    {
        return match ($this) {
            self::PENDING, self::INITIALIZED => ['delete'],
            self::WAITING_FOR_CONFIRMATION => ['accept', 'reject'],
            self::COMPLETED => ['refund'],
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

}
