<?php

namespace xGrz\PayU\Enums;


use xGrz\PayU\Interfaces\WithActions;
use xGrz\PayU\Interfaces\WithColors;
use xGrz\PayU\Interfaces\WithLabel;
use xGrz\PayU\Traits\HasActions;
use xGrz\PayU\Traits\HasLabel;
use xGrz\PayU\Traits\WithNames;

enum RefundStatus: int implements WithColors, WithActions, WithLabel
{
    use WithNames, HasLabel, HasActions;

    case INITIALIZED = 0;
    case SENT = 1;
    case PENDING = 2;
    case CANCELED = 3;
    case FINALIZED = 4;
    case ERROR = 5;

    case SCHEDULED = 10;
    case RETRY = 11;

    public function actions(): array
    {
        return match ($this) {
            self::INITIALIZED, self::SCHEDULED => ['send', 'delete', 'in-progress'],
            self::SENT, self::PENDING, self::FINALIZED => ['success'],
            self::CANCELED => ['failed'],
            self::ERROR => ['failed', 'retry', 'delete'],
            self::RETRY => ['send', 'failed'],
            default => []
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::INITIALIZED, self::SCHEDULED => 'gray',
            self::SENT, self::PENDING => 'info',
            self::CANCELED, self::RETRY => 'warning',
            self::FINALIZED => 'success',
            self::ERROR => 'danger',
            default => ''
        };
    }

    public function getLangKey(): string
    {
        return 'refunds.status';
    }

}
