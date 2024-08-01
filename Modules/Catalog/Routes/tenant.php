<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('catalog')->group(function () {
            //Route::get('/', [CatalogController::class, 'index']);
        });
    });
});
