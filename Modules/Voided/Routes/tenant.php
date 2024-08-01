<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('voided')->group(function () {
            //Route::get('/', [VoidedController::class, 'index']);
        });
    });
});
