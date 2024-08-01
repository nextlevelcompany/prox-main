<?php

use Illuminate\Support\Facades\Route;

Route::prefix('system')->group(function() {
    Route::get('/', 'SystemController@index');
});
