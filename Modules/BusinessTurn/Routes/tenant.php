<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('bussinessturn')->group(function () {
            //Route::get('/', [BussinessTurnController::class, 'index']);
        });
    });
});
