<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('padron')->group(function () {
            //Route::get('/', [PadronController::class, 'index']);
        });
    });
});
