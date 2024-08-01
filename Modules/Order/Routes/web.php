<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('mi_tienda_pe')->group(function () {
                Route::get('/', 'MiTiendaPeController@index')->name('tenant.mi_tienda_pe.configuration.index');
                Route::post('/', 'MiTiendaPeController@tables');
                Route::post('/save', 'MiTiendaPeController@store');
                Route::post('/getdata', 'MiTiendaPeController@getData');
            });

            Route::prefix('orders')->group(function () {
                Route::get('/', 'OrderController@index')->name('tenant_orders_index');
                Route::get('/columns', 'OrderController@columns');
                Route::get('/records', 'OrderController@records');
                Route::get('/record/{order}', 'OrderController@record');
                Route::get('/pdf/{id}', 'OrderController@pdf');
                Route::post('/warehouse', 'OrderController@searchWarehouse');
                Route::get('/tables', 'OrderController@tables');
                Route::get('/tables/item/{internal_id}', 'OrderController@item');
            });


            //Status Orders
            Route::post('statusOrder/update/', 'OrderController@updateStatusOrders');
            Route::get('statusOrder/records', 'StatusOrdersController@records');

        });
    });
}
