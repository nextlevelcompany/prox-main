<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::get('/', 'AuthController@index');
});
