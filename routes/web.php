<?php

use Illuminate\Support\Facades\Route;
use xGrz\PayU\Http\Controllers\MethodsController;
use xGrz\PayU\Http\Controllers\PaymentController;
use xGrz\PayU\Http\Controllers\PayoutController;
use xGrz\PayU\Http\Controllers\RefundController;

Route::name('payu.')
    ->middleware(['web'])
    ->group(function () {

        Route::name('payments.')
            ->prefix('payu-payments')
            ->group(function () {
                Route::get('', [PaymentController::class, 'index'])->name('index');
                Route::get('create', [PaymentController::class, 'create'])->name('create');
                Route::post('store', [PaymentController::class, 'store'])->name('store');
                Route::post('store-fake', [PaymentController::class, 'storeFake'])->name('storeFake');
                Route::patch('{transaction}/accept', [PaymentController::class, 'accept'])->name('accept');
                Route::delete('{transaction}/reject', [PaymentController::class, 'reject'])->name('reject');
                Route::get('{transaction}', [PaymentController::class, 'show'])->name('show');
                Route::delete('{transaction}', [PaymentController::class, 'destroy'])->name('destroy');
            });


        Route::name('refunds.')
            ->prefix('payu-refunds')
            ->group(function () {
                Route::get('', [RefundController::class, 'index'])->name('index');
                Route::get('{refund}', [RefundController::class, 'retry'])->name('retry');
                Route::post('{transaction}/create', [RefundController::class, 'store'])->name('store');
                Route::delete('{refund}', [RefundController::class, 'destroy'])->name('destroy');
            });

        Route::name('payouts.')
            ->prefix('payu-payouts')
            ->group(function () {
                Route::get('', [PayoutController::class, 'index'])->name('index');
                Route::post('', [PayoutController::class, 'store'])->name('store');
                Route::patch('{payout}/status', [PayoutController::class, 'update'])->name('status');
                Route::patch('{payout}/retry', [PayoutController::class, 'retry'])->name('retry');
                Route::delete('{payout}', [PayoutController::class, 'destroy'])->name('destroy');
            });

        Route::name('methods.')
            ->prefix('payu-methods')
            ->group(function () {
                Route::get('', [MethodsController::class, 'index'])->name('index');
                Route::get('sync', [MethodsController::class, 'synchronize'])->name('synchronize');
            });
    });
