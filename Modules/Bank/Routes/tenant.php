<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('bank')->group(function () {
            //Route::get('/', [BankController::class, 'index']);
        });
    });
});
