<?php

namespace xGrz\PayU\Traits;

trait WithLabels
{
    public function getLabel(): string
    {
        return __($this->name);
    }

}
