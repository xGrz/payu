<?php

namespace xGrz\PayU\Enums;


use xGrz\PayU\Interfaces\WithActions;
use xGrz\PayU\Interfaces\WithColors;
use xGrz\PayU\Traits\WithActionsDetect;
use xGrz\PayU\Traits\WithLabels;
use xGrz\PayU\Traits\WithStatusNames;

enum RefundStatus: int implements WithColors, WithActions
{
    use WithStatusNames, WithLabels, WithActionsDetect;

    case INITIALIZED = 0;
    case SCHEDULED = 10;
    case SENT = 1;
    case PENDING = 2;
    case CANCELED = 3;
    case FINALIZED = 4;
    case ERROR = 5;

    public function actions(): array
    {
        return match ($this) {
            self::INITIALIZED, self::SCHEDULED => ['send', 'delete'],
            self::SENT, self::PENDING => ['update'], // TODO: Check if it is required
            default => []
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::INITIALIZED, self::SCHEDULED => 'gray',
            self::SENT, self::PENDING => 'info',
            self::CANCELED => 'warning',
            self::FINALIZED => 'success',
            self::ERROR => 'danger',
            default => ''
        };
    }

}
