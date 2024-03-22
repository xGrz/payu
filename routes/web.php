<?php

use Illuminate\Support\Facades\Route;
use xGrz\PayU\Http\Controllers\PaymentController;


Route::name('payu.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('payu-payments', [PaymentController::class, 'index'])->name('index');
    });

