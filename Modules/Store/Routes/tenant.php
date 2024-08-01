<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('store')->group(function () {
            //Route::get('/', [StoreController::class, 'index']);
        });
    });
});
