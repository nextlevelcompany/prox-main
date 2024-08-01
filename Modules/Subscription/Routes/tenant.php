<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('subscription')->group(function () {
            //Route::get('/', [SubscriptionController::class, 'index']);
        });
    });
});
