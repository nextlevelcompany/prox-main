<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('orderform')->group(function () {
            //Route::get('/', [OrderFormController::class, 'index']);
        });
    });
});
