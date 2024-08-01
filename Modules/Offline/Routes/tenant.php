<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('offline')->group(function () {
            //Route::get('/', [OfflineController::class, 'index']);
        });
    });
});
