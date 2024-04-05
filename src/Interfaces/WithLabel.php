<?php

namespace xGrz\PayU\Interfaces;

interface WithLabel
{
    public function getLangKey(): string;

    public function getLabel(): string;
}
