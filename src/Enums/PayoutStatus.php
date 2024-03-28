<?php

namespace xGrz\PayU\Enums;

use xGrz\PayU\Interfaces\WithActions;
use xGrz\PayU\Interfaces\WithColors;
use xGrz\PayU\Traits\WithActionsDetect;
use xGrz\PayU\Traits\WithLabels;
use xGrz\PayU\Traits\WithStatusNames;

enum PayoutStatus: int implements WithColors, WithActions
{
    use WithStatusNames, WithLabels, WithActionsDetect;

    case INIT = 0;
    case SCHEDULED = 10;
    case PENDING = 1;
    case WAITING = 2;
    case CANCELED = 5;
    case REALIZED = 4;

    public function actions(): array
    {
        return match($this) {
            self::INIT => ['send', 'delete'],
            self::SCHEDULED => ['send', 'delete'],
            self::PENDING,self::WAITING => ['update', 'refresh-status'],
            self::REALIZED, self::CANCELED => []
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING, self::WAITING => 'info',
            self::CANCELED => 'danger',
            self::REALIZED => 'success',
            default => 'gray'
        };
    }

    public static function updatable(): array
    {
        $updatable = [];
        foreach (self::cases() as $case) {
            if ($case->actionAvailable('update')) $updatable[] = $case;
        }
        return $updatable;
    }

    public static function sendable(): array
    {
        $sendable = [];
        foreach (self::cases() as $case) {
            if ($case->actionAvailable('send')) $sendable[] = $case;
        }
        return $sendable;
    }
}
