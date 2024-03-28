<?php

namespace xGrz\PayU\Actions;

use Illuminate\Support\Facades\DB;
use xGrz\PayU\Api\Actions\GetPaymentMethods;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Api\Responses\GetPaymentMethodsResponse;
use xGrz\PayU\Models\Method;
use xGrz\PayU\Services\LoggerService;

class SyncPaymentMethods
{
    /**
     * Retrieves payment methods from PayU. Methods are stored in database. All historical methods are marked as unavailable;
     */
    public static function handle(): bool
    {
        try {
            $methods = GetPaymentMethods::callApi();
        } catch (PayUGeneralException $e) {
            LoggerService::error('Payment methods not updated', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
        return self::syncLocalPaymentMethods($methods);
    }

    /**
     * Sync provided methods (from api) to database
     */
    private static function syncLocalPaymentMethods(GetPaymentMethodsResponse $methods): bool
    {
        if (!count($methods->toArray())) {
            LoggerService::notice('Payment methods empty collection', [
                'error' => 'Api return empty collection of payment methods'
            ]);
            return false;
        }

        try {
            DB::transaction(function () use ($methods) {
                Method::query()->update(['available' => false]);
                Method::upsert($methods->toArray(), ['code'], ['image', 'name', 'available', 'min_amount', 'max_amount']);
            });
            LoggerService::notice('Payment methods successfully synchronized');
            return true;
        } catch (\Throwable $e) {
            LoggerService::error('Payment methods local store failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

}
