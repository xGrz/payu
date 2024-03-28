<?php

namespace xGrz\PayU\Traits;

trait HasActions
{
    public function hasAction(string $actionName): bool
    {
        return in_array(strtolower($actionName), self::actions());
    }

}
