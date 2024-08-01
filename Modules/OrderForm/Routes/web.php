<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::get('order-forms/print/{external_id}/{format?}', 'OrderFormController@toPrint');
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('order-forms')->group(function () {

                Route::get('/', 'OrderFormController@index')->name('tenant.order_forms.index');
                Route::get('columns', 'OrderFormController@columns');
                Route::get('records', 'OrderFormController@records');
                Route::get('create/{id?}', 'OrderFormController@create')->name('tenant.order_forms.create');

                Route::post('tables', 'OrderFormController@tables');
                Route::get('table/{table}', 'OrderFormController@table');
                Route::post('/', 'OrderFormController@store');
                Route::get('record/{id}', 'OrderFormController@record');
                Route::get('item/tables', 'OrderFormController@item_tables');
                Route::get('option/tables', 'OrderFormController@option_tables');
                Route::get('search/customers', 'OrderFormController@searchCustomers');
                Route::get('search/customer/{id}', 'OrderFormController@searchCustomerById');
                Route::get('download/{external_id}/{format?}', 'OrderFormController@download');
                Route::post('email', 'OrderFormController@email');

                Route::get('dispatch-create/{id?}', 'OrderFormController@dispatchCreate');

            });
        });
    });
}
