<?php

namespace xGrz\PayU\Enums;


use xGrz\PayU\Interfaces\WithActions;
use xGrz\PayU\Interfaces\WithColors;
use xGrz\PayU\Traits\HasActions;
use xGrz\PayU\Traits\HasLabels;
use xGrz\PayU\Traits\WithNames;

enum RefundStatus: int implements WithColors, WithActions
{
    use WithNames, HasLabels, HasActions;

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
            self::INITIALIZED, self::SCHEDULED => ['send', 'delete', 'in-progress'],
            self::SENT, self::PENDING, self::FINALIZED => ['success'],
            self::CANCELED, self::ERROR => ['failed'],
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
