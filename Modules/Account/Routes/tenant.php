<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('account')->group(function () {
            //Route::get('/', [AccountController::class, 'index']);
        });
    });
});
