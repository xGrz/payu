<?php

namespace xGrz\PayU\Enums;

use xGrz\PayU\Interfaces\WithActions;
use xGrz\PayU\Interfaces\WithColors;
use xGrz\PayU\Interfaces\WithLabel;
use xGrz\PayU\Traits\HasActions;
use xGrz\PayU\Traits\HasLabel;
use xGrz\PayU\Traits\WithNames;

enum PayoutStatus: int implements WithColors, WithActions, WithLabel
{
    use WithNames, HasLabel, HasActions;

    case INIT = 0;
    case PENDING = 1;
    case WAITING = 2;
    case CANCELED = 5;
    case REALIZED = 4;
    case SCHEDULED = 10;
    case RETRY = 11;

    public function actions(): array
    {
        return match($this) {
            self::INIT, self::SCHEDULED => ['send', 'delete'],
            self::PENDING, self::WAITING => ['processing', 'success'],
            self::CANCELED => ['delete', 'retry', 'failed'],
            self::REALIZED => ['success'],
            self::RETRY => ['send', 'failed'],
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
            if ($case->hasAction('retry')) $updatable[] = $case;
        }
        return $updatable;
    }

    public static function sendable(): array
    {
        $sendable = [];
        foreach (self::cases() as $case) {
            if ($case->hasAction('send')) $sendable[] = $case;
        }
        return $sendable;
    }

    public function getLangKey(): string
    {
        return 'payouts.status';
    }
}
