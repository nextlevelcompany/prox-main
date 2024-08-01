<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('production')->group(function () {
            //Route::get('/', [ProductionController::class, 'index']);
        });
    });
});
