<?php


if (!function_exists('humanAmount')) {

    function humanAmount(int|float $amount, string $currency = null, string $locale = null): mixed
    {
        return \Illuminate\Support\Number::currency($amount, $currency ?? 'PLN' ,$locale ?? app()->getLocale());
    }

}
