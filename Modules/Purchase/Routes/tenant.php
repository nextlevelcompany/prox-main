<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('purchase')->group(function () {
            //Route::get('/', [PurchaseController::class, 'index']);
        });
    });
});
