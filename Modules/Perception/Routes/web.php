<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('perceptions')->group(function () {
                //Perceptions
                Route::get('/', 'PerceptionController@index')->name('tenant.perceptions.index');
                Route::get('/columns', 'PerceptionController@columns');
                Route::get('/records', 'PerceptionController@records');
                Route::get('/create', 'PerceptionController@create')->name('tenant.perceptions.create');
                Route::get('/tables', 'PerceptionController@tables');
                Route::get('/record/{perception}', 'PerceptionController@record');
                Route::post('/', 'PerceptionController@store');
                Route::delete('/{perception}', 'PerceptionController@destroy');
                Route::get('/document/tables', 'PerceptionController@document_tables');
                Route::get('/table/{table}', 'PerceptionController@table');
            });
        });
    });
}
