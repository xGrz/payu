<?php

namespace xGrz\PayU\Interfaces;

interface WithActions
{
    public function actions(): array;

    public function actionAvailable(string $actionName): bool;
}
