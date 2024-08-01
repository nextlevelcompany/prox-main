<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('services')->group(function () {
            //Route::get('/', [ServicesController::class, 'index']);
        });
    });
});
