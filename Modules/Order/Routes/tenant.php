<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('order')->group(function () {
            //Route::get('/', [OrderController::class, 'index']);
        });
    });
});
