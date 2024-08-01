<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('report')->group(function () {
            //Route::get('/', [ReportController::class, 'index']);
        });
    });
});
