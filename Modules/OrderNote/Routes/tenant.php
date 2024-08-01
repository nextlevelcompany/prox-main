<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware(['auth', 'locked.tenant'])->group(function () {
        Route::prefix('ordernote')->group(function () {
            //Route::get('/', [OrderNoteController::class, 'index']);
        });
    });
});
