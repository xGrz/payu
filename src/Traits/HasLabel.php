<?php

namespace xGrz\PayU\Traits;

trait HasLabel
{
    public function getLabel(): string
    {
        return __('payu::' . self::getLangKey() . '.' . $this->name);
    }

}
