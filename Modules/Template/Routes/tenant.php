<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('template')->group(function () {
            //Route::get('/', [TemplateController::class, 'index']);
        });
    });
});
