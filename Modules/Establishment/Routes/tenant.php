<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('establishment')->group(function () {
            //Route::get('/', [EstablishmentController::class, 'index']);
        });
    });
});
