<?php

use Illuminate\Support\Facades\Route;
use xGrz\PayU\Http\Controllers\PaymentController;


Route::name('payu.payments.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('payu-payments', [PaymentController::class, 'index'])->name('index');
        Route::get('payu-payments/{transaction}', [PaymentController::class, 'show'])->name('show');
        Route::post('payu-payments/store', [PaymentController::class, 'store'])->name('store');
        Route::patch('payu-payments/{transaction}/accept', [PaymentController::class, 'accept'])->name('accept');
        Route::delete('payu-payments/{transaction}/reject', [PaymentController::class, 'reject'])->name('reject');
        Route::delete('payu-payments/{transaction}', [PaymentController::class, 'destroy'])->name('destroy');
    });


