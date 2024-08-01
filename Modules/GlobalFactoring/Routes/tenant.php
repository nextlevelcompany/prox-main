<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('globalfactoring')->group(function () {
            //Route::get('/', [GlobalFactoringController::class, 'index']);
        });
    });
});
