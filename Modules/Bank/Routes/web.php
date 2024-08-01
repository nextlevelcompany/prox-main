<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('banks')->group(function () {
                Route::get('/records', 'BankController@records');
                Route::get('/record/{bank}', 'BankController@record');
                Route::post('/', 'BankController@store');
                Route::delete('/{bank}', 'BankController@destroy');
            });

            Route::prefix('bank_accounts')->group(function () {
                Route::get('/', 'BankAccountController@index')->name('tenant.bank_accounts.index');
                Route::get('/records', 'BankAccountController@records');
                Route::get('/create', 'BankAccountController@create');
                Route::get('/tables', 'BankAccountController@tables');
                Route::get('/record/{bank_account}', 'BankAccountController@record');
                Route::post('/', 'BankAccountController@store');
                Route::delete('/{bank_account}', 'BankAccountController@destroy');
            });
        });
    });
}
