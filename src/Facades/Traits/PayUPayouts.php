<?php

namespace xGrz\PayU\Facades\Traits;

use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Jobs\SendPayoutJob;
use xGrz\PayU\Jobs\UpdatePayoutStatusJob;
use xGrz\PayU\Models\Payout;

trait PayUPayouts
{

    public static function payout(int|float $amount): void
    {
        if (!Config::getShopId()) return;
        Payout::create(['amount' => $amount]);
    }

    public static function forceUpdatePayoutStatus(Payout $payout, int $delay = null): bool
    {
        if (!Config::getShopId()) return false;
        if (!$payout->status->hasAction('processing')) return false;

        UpdatePayoutStatusJob::dispatch($payout)
            ->delay(now()->addSeconds(is_null($delay) ? Config::getPayoutInterval() : $delay));

        return true;
    }

    public static function retryPayout(Payout $payout, int $delay = null): bool
    {
        if (!Config::getShopId()) return false;
        if (!$payout->status->hasAction('retry')) return false;

        $payout
            ->update(['status' => PayoutStatus::RETRY]);

        SendPayoutJob::dispatch($payout)
            ->delay(now()->addSeconds(is_null($delay) ? Config::getPayoutRetryDelay() : $delay));

        return true;
    }

    public static function cancelPayout(Payout $payout): bool
    {
        if (!Config::getShopId()) return false;
        if (!$payout->status->hasAction('delete')) return false;

        $payout->delete();
        return true;
    }

}
