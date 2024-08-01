<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('users')->group(function () {
                Route::get('/', 'UserController@index')->name('tenant.users.index');
                Route::get('/create', 'UserController@create')->name('tenant.users.create');
                Route::get('/tables', 'UserController@tables');
                Route::get('/record/{user}', 'UserController@record');
                Route::post('/', 'UserController@store');
                Route::post('/token/{user}', 'UserController@regenerateToken');
                Route::get('/records', 'UserController@records');
                Route::delete('/{user}', 'UserController@destroy');
                Route::post('/change-active', 'UserController@changeActive');
            });
        });
    });
}
