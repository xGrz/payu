<?php

namespace xGrz\PayU\Traits;

trait HasLabels
{
    public function getLabel(): string
    {
        return __($this->name);
    }

}
