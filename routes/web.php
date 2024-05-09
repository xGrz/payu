<?php

use Illuminate\Support\Facades\Route;
use xGrz\PayU\Facades\Config;


Route::name(Config::getRouteName())
    ->middleware(['web'])
    ->controller(Config::getPaymentController())
    ->group(function () {

        Route::name('payments.')
            ->prefix(Config::getUri('payments'))
            ->group(function () {
                Route::get('', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::post('store-fake', 'storeFake')->name('storeFake');
                Route::patch('{transaction}/accept', 'accept')->name('accept');
                Route::delete('{transaction}/reject', 'reject')->name('reject');
                Route::get('{transaction}/payMethod', 'requestPayMethod')->name('method');
                Route::get('{transaction}', 'show')->name('show');
                Route::delete('{transaction}', 'destroy')->name('destroy');
            });


        Route::get(Config::getUri('refunds'), Config::getRefundController())->name('refunds.index');

        Route::name('payouts.')
            ->prefix(Config::getUri('payouts'))
            ->controller(Config::getPayoutController())
            ->group(function () {
                Route::get('', 'index')->name('index');
                Route::post('', 'store')->name('store');
                Route::patch('{payout}/status', 'update')->name('status');
                Route::patch('{payout}/retry', 'retry')->name('retry');
                Route::delete('{payout}', 'destroy')->name('destroy');
            });

        Route::name('methods.')
            ->prefix(Config::getUri('methods'))
            ->controller(Config::getMethodsController())
            ->group(function () {
                Route::get('', 'index')->name('index');
                Route::get('sync', 'synchronize')->name('synchronize');
                Route::post('{method}', 'activate')->name('activate');
                Route::delete('{method}', 'deactivate')->name('deactivate');
            });
    });
