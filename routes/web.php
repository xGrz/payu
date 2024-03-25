<?php

use Illuminate\Support\Facades\Route;
use xGrz\PayU\Http\Controllers\PaymentController;
use xGrz\PayU\Http\Controllers\RefundController;

Route::name('payu.')
    ->middleware(['web'])
    ->group(function () {

        Route::name('payments.')
            ->prefix('payu-payments')
            ->group(function () {
                Route::get('', [PaymentController::class, 'index'])->name('index');
                Route::post('store', [PaymentController::class, 'store'])->name('store');
                Route::patch('{transaction}/accept', [PaymentController::class, 'accept'])->name('accept');
                Route::delete('{transaction}/reject', [PaymentController::class, 'reject'])->name('reject');
                Route::get('{transaction}', [PaymentController::class, 'show'])->name('show');
                Route::delete('{transaction}', [PaymentController::class, 'destroy'])->name('destroy');
            });


        Route::name('refunds.')
            ->prefix('payu-refunds')
            ->group(function () {
                Route::get('{transaction}', [RefundController::class, 'create'])->name('create');
                Route::post('{transaction}/create', [RefundController::class, 'store'])->name('store');
                Route::delete('{refund}', [RefundController::class, 'destroy'])->name('destroy');
            });

    });
