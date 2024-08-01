<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/ecommerce', function (Request $request) {
    return $request->user();
});
