<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('inventory')->group(function () {
            //Route::get('/', [InventoryController::class, 'index']);
        });
    });
});
