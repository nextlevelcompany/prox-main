<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('summary')->group(function () {
            //Route::get('/', [SummaryController::class, 'index']);
        });
    });
});
