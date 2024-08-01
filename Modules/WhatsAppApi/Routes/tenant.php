<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('whatsappapi')->group(function () {
            //Route::get('/', [WhatsAppApiController::class, 'index']);
        });
    });
});
