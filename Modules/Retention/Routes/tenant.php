<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('retention')->group(function () {
            //Route::get('/', [RetentionController::class, 'index']);
        });
    });
});
