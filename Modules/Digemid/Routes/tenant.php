<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('digemid')->group(function () {
            //Route::get('/', [DigemidController::class, 'index']);
        });
    });
});
