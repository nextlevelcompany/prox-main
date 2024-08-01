<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('cash')->group(function () {
            //Route::get('/', [CashController::class, 'index']);
        });
    });
});
