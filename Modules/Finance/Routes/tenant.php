<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('finance')->group(function () {
            //Route::get('/', [FinanceController::class, 'index']);
        });
    });
});
