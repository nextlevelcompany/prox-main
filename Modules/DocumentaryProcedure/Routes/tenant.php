<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('documentaryprocedure')->group(function () {
            //Route::get('/', [DocumentaryProcedureController::class, 'index']);
        });
    });
});
