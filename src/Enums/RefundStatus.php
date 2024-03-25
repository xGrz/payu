<?php

namespace xGrz\PayU\Enums;


use xGrz\PayU\Interfaces\WithColors;
use xGrz\PayU\Traits\WithStatusNames;

enum RefundStatus: int implements WithColors
{
    use WithStatusNames;

    case INITIALIZED = 0;
    case SENT = 1;
    case PENDING = 2;
    case CANCELED = 3;
    case FINALIZED = 4;
    case ERROR = 5;

    public function getColor(): string
    {
        return match ($this) {
            self::INITIALIZED => 'gray',
            self::SENT, self::PENDING => 'info',
            self::CANCELED => 'warning',
            self::FINALIZED => 'success',
            self::ERROR => 'danger',
            default => ''
        };
    }

    public function isDeletable(): bool
    {
        return match ($this) {
            self::INITIALIZED => true,
            default => false
        };
    }

}
