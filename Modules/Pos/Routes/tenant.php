<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('pos')->group(function () {
            //Route::get('/', [PosController::class, 'index']);
        });
    });
});
