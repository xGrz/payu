<?php

use Illuminate\Support\Facades\Route;
use xGrz\PayU\Http\Controllers\NotificationWebhookController;
use xGrz\PayU\Http\Middleware\PayUWhitelist;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware(['api', PayUWhitelist::class])
    ->prefix(config('payu.routing.notifications.endpoint_name', 'payu-payment-notification'))
    ->name(config('payu.routing.notifications.route_name', 'payu.notification'))
    ->group(function () {
        Route::post('{transaction}', NotificationWebhookController::class);
    })->missing(fn() => response('Missing', 200));
