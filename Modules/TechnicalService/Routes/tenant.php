<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('technicalservice')->group(function () {
            //Route::get('/', [TechnicalServiceController::class, 'index']);
        });
    });
});
