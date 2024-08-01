<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('retentions')->group(function () {
                //Retentions
                Route::get('/', 'RetentionController@index')->name('tenant.retentions.index');
                Route::get('/columns', 'RetentionController@columns');
                Route::get('/records', 'RetentionController@records');
                Route::get('/create', 'RetentionController@create')->name('tenant.retentions.create');
                Route::get('/tables', 'RetentionController@tables');
                Route::get('/record/{retention}', 'RetentionController@record');
                Route::post('/', 'RetentionController@store');
                Route::delete('/{retention}', 'RetentionController@destroy');
                Route::get('/document/tables', 'RetentionController@document_tables');
                Route::get('/table/{table}', 'RetentionController@table');
            });
        });
    });
}
