<?php

namespace xGrz\PayU\Traits;

trait WithActionsDetect
{
    public function actionAvailable(string $actionName): bool
    {
        return in_array(strtolower($actionName), self::actions());
    }

}
