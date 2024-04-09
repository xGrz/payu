<?php

namespace xGrz\PayU\Facades;

use Illuminate\Support\Facades\Facade;
use xGrz\PayU\Api\Exceptions\PayUGeneralException;
use xGrz\PayU\Services\AuthService;
use xGrz\PayU\Services\ConfigService;

/**
 * @method static getClientId()
 * @method static getServiceDomain()
 * @method static getCacheKey()
 * @method static getClientSecret()
 * @method static getMerchantPosId()
 * @method static getShopId()
 * @method static getSignatureKey()
 * @method static shouldBeLogged()
 * @method static getPayoutInterval()
 * @method static getPayoutSendDelay()
 * @method static getRefundSendDelay()
 * @method static getPayoutRetryDelay()
 * @method static getRefundRetryDelay()
 * @method static getBalanceCacheKey()
 * @method static getTransactionMethodCheckDelay()
 * @method static hasPayMethods()
 * @method static getPaymentController()
 * @method static getRefundController()
 * @method static getPayoutController()
 * @method static getMethodsController()
 * @method static getUri(string $string): string
 * @method static getRouteRootNaming()
 * @method static isSandboxMode()
 */
class Config extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ConfigService::class;
    }

    /**
     * @throws PayUGeneralException
     */
    public static function getToken(): string
    {
        try {
            return AuthService::getToken();
        } catch (PayUGeneralException $exception) {
            // When exception is thrown invalidate token and try again;
            AuthService::invalidateToken();
            return AuthService::getToken();
        }
    }

}
