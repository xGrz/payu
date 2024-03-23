<?php

use Illuminate\Support\Facades\Route;
use xGrz\PayU\Http\Controllers\NotificationController;

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


Route::middleware('api')
    ->prefix(config('payu.routing.notification.endpoint_name', 'payu-payment-notification'))
    ->name(config('payu.routing.notification.route_name', 'payu.notification'))
    ->group(function () {
        Route::post('{transaction}', NotificationController::class);
    })->missing(fn() => response('Missing', 200));
