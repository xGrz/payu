<?php

namespace xGrz\PayU\Traits;

trait Arrayable
{
    public function toArray(): array
    {
        return array_filter($this->data, fn($value) => !is_null($value));
    }

}
