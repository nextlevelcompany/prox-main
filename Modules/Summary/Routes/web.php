<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('summaries')->group(function () {
                Route::get('/', 'SummaryController@index')->name('tenant.summaries.index')->middleware('redirect.level', 'tenant.internal.mode');
                Route::get('/records', 'SummaryController@records');
                Route::post('/documents', 'SummaryController@documents');
                Route::post('/', 'SummaryController@store');
                Route::get('/status/{summary}', 'SummaryController@status');
                Route::get('/columns', 'SummaryController@columns');
                Route::delete('/{summary}', 'SummaryController@destroy');
                Route::get('/record/{summary}', 'SummaryController@record');
                Route::get('/regularize/{summary}', 'SummaryController@regularize');
                Route::get('/cancel-regularize/{summary}', 'SummaryController@cancelRegularize');
                Route::get('/tables', 'SummaryController@tables');
            });
        });
    });
}
