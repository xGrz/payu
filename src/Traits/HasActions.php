<?php

namespace xGrz\PayU\Traits;

trait HasActions
{
    public function hasAction(string $actionName): bool
    {
        return in_array(strtolower($actionName), self::actions());
    }

    public static function withAction(string $actionName): array
    {
        $statuses = [];
        foreach (self::cases() as $case) {
            if ($case->hasAction($actionName)) {
                $statuses[] = $case;
            }
        }
        return $statuses;
    }

}
