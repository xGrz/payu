<?php

namespace xGrz\PayU\Http\Controllers;

use App\Http\Controllers\Controller;
use xGrz\PayU\Facades\Config;

class PaymentController extends Controller
{
    public function index()
    {
        dd(
            Config::getShopId(),
            Config::getMerchantPosId(),
            Config::getClientId(),
            Config::getClientSecret(),
            Config::getServiceDomain(),
            Config::getSignatureKey(),
            Config::shouldBeLogged(),
            Config::getCacheKey(),
            Config::getToken()
        );
    }
}
