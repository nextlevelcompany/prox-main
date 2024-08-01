<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('person')->group(function () {
            //Route::get('/', [PersonController::class, 'index']);
        });
    });
});
