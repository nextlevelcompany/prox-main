<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('expense')->group(function () {
            //Route::get('/', [ExpenseController::class, 'index']);
        });
    });
});
