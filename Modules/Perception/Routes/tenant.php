<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('perception')->group(function () {
            //Route::get('/', [PerceptionController::class, 'index']);
        });
    });
});
