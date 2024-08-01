<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('dispatch')->group(function () {
            //Route::get('/', [DispatchController::class, 'index']);
        });
    });
});
