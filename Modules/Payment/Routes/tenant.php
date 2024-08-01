<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('payment')->group(function () {
            //Route::get('/', [PaymentController::class, 'index']);
        });
    });
});
