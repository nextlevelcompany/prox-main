<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('item')->group(function () {
            //Route::get('/', [ItemController::class, 'index']);
        });
    });
});
