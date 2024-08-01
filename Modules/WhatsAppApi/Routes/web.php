<?php

use Illuminate\Support\Facades\Route;

Route::prefix('whatsappapi')->group(function() {
    Route::get('/', 'WhatsAppApiController@index');
});
