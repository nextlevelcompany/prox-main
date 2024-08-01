<?php

use Illuminate\Support\Facades\Route;

Route::prefix('addonexample')->group(function() {
    Route::get('/', 'AddonExampleController@index');
});
