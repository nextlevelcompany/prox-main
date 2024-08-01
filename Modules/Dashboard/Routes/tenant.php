<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('dashboard')->group(function () {
            //Route::get('/', [DashboardController::class, 'index']);
        });
    });
});
