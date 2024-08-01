<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('salenote')->group(function () {
            //Route::get('/', [SaleNoteController::class, 'index']);
        });
    });
});
