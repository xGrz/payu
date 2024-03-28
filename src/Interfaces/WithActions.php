<?php

namespace xGrz\PayU\Interfaces;

interface WithActions
{
    public function actions(): array;

    public function hasAction(string $actionName): bool;
}
