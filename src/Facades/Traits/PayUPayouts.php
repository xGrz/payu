<?php

namespace xGrz\PayU\Facades\Traits;

use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Enums\PayoutStatus;
use xGrz\PayU\Facades\Config;
use xGrz\PayU\Jobs\SendPayoutJob;
use xGrz\PayU\Jobs\UpdatePayoutStatusJob;
use xGrz\PayU\Models\Payout;

trait PayUPayouts
{
    public static function payout(int|float $amount): bool
    {
        if (!Config::getShopId()) return false;

        try {
            return (bool)Payout::create(['amount' => $amount]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function retryPayout(Payout $payout, int $delay = null): bool
    {
        if (!$payout->status->hasAction('retry')) {
            throw new PayUGeneralException('Cannot retry payout. Action [retry] impossible');
        }

        $payout
            ->update(['status' => PayoutStatus::RETRY]);

        SendPayoutJob::dispatch($payout)
            ->delay(is_null($delay) ? Config::getPayoutSendDelay() : 0);


        return true;
    }

    public static function payoutStatusCheck(Payout $payout, int $delay = null): bool
    {
        UpdatePayoutStatusJob::dispatch($payout)
            ->delay(is_null($delay) ? Config::getPayoutInterval() : $delay);

        return true;
    }
    public static function cancelPayout(Payout $payout): bool
    {
        if (!$payout->status->hasAction('delete')) return false;

        $payout->delete();
        return true;
    }

}
