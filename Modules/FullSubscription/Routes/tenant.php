<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('fullsubscription')->group(function () {
            //Route::get('/', [FullSubscriptionController::class, 'index']);
        });
    });
});
