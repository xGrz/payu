<?php

namespace xGrz\PayU\Services;

use Illuminate\Support\Facades\Log;
use xGrz\PayU\Facades\Config;

class LoggerService
{

    public static function info(string $message, array $context = []): void
    {
        if (!Config::shouldBeLogged()) return;
        if (!isset($context['userId'])) $context['userId'] = auth()->id();
        Log::info('PayU | ' . $message, $context);
    }

    public static function notice(string $message, array $context = []): void
    {
        if (!Config::shouldBeLogged()) return;
        if (!isset($context['userId'])) $context['userId'] = auth()->id();
        Log::notice('PayU | ' . $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        if (!Config::shouldBeLogged()) return;
        if (!isset($context['userId'])) $context['userId'] = auth()->id();
        Log::warning('PayU | ' . $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        if (!isset($context['userId'])) $context['userId'] = auth()->id();
        Log::error('PayU | ' . $message, $context);
    }
}
