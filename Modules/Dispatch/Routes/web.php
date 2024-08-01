<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            Route::prefix('dispatches')->group(function () {
                Route::get('', 'DispatchController@index')->name('tenant.dispatches.index');
                Route::get('/columns', 'DispatchController@columns');
                Route::get('/records', 'DispatchController@records');
                Route::get('/create/{document?}/{type?}/{dispatch?}', 'DispatchController@create');
                Route::post('/tables', 'DispatchController@tables');
                Route::post('', 'DispatchController@store');
                Route::get('/record/{id}', 'DispatchController@record');
                Route::post('/sendSunat/{document}', 'DispatchController@sendDispatchToSunat');
                Route::post('/email', 'DispatchController@email');
                Route::get('/generate/{sale_note}', 'DispatchController@generate');
                Route::get('/record/{id}/tables', 'DispatchController@generateDocumentTables');
                Route::post('/record/{id}/set-document-id', 'DispatchController@setDocumentId');
                Route::get('/client/{id}', 'DispatchController@dispatchesByClient');
                Route::post('/items', 'DispatchController@getItemsFromDispatches');
                Route::post('/getDocumentType', 'DispatchController@getDocumentTypeToDispatches');
                Route::get('/data_table', 'DispatchController@data_table');
                Route::get('/search/customers', 'DispatchController@searchCustomers');
                Route::get('/search/customer/{id}', 'DispatchController@searchClientById');
                Route::post('/status_ticket', 'Api\DispatchController@statusTicket');
                Route::get('create_new/{table}/{id}', 'DispatchController@createNew');
                Route::get('/get_origin_addresses/{establishment_id}', 'DispatchController@getOriginAddresses');
                Route::get('/get_delivery_addresses/{person_id}', 'DispatchController@getDeliveryAddresses');
            });

            Route::prefix('dispatch_carrier')->group(function () {
                Route::get('', 'DispatchCarrierController@index')->name('tenant.dispatch_carrier.index');
                Route::get('/columns', 'DispatchCarrierController@columns');
                Route::get('/records', 'DispatchCarrierController@records');
                Route::get('/create/{document?}/{type?}/{dispatch?}', 'DispatchCarrierController@create');
                Route::post('/tables', 'DispatchCarrierController@tables');
                Route::post('', 'DispatchCarrierController@store');
                Route::get('/record/{id}', 'DispatchCarrierController@record');
                Route::post('/sendSunat/{document}', 'DispatchCarrierController@sendDispatchToSunat');
                Route::post('/email', 'DispatchCarrierController@email');
                Route::get('/generate/{sale_note}', 'DispatchCarrierController@generate');
                Route::get('/record/{id}/tables', 'DispatchCarrierController@generateDocumentTables');
                Route::post('/record/{id}/set-document-id', 'DispatchCarrierController@setDocumentId');
                Route::get('/client/{id}', 'DispatchCarrierController@dispatchesByClient');
                Route::post('/items', 'DispatchCarrierController@getItemsFromDispatches');
                Route::post('/getDocumentType', 'DispatchCarrierController@getDocumentTypeToDispatches');
                Route::get('/data_table', 'DispatchCarrierController@data_table');
                Route::get('/search/customers', 'DispatchCarrierController@searchCustomers');
                Route::get('/search/customer/{id}', 'DispatchCarrierController@searchClientById');
                Route::post('/status_ticket', 'Api\DispatchCarrierController@statusTicket');
                Route::get('create_new/{table}/{id}', 'DispatchCarrierController@createNew');
                Route::get('/get_origin_addresses/{establishment_id}', 'DispatchCarrierController@getOriginAddresses');
                Route::get('/get_delivery_addresses/{person_id}', 'DispatchCarrierController@getDeliveryAddresses');
            });

            Route::prefix('drivers')->group(function () {
                Route::get('/', 'DriverController@index')->name('tenant.drivers.index');
                Route::get('columns', 'DriverController@columns');
                Route::get('records', 'DriverController@records');
                Route::get('record/{id}', 'DriverController@record');
                Route::get('tables', 'DriverController@tables');
                Route::post('/', 'DriverController@store');
                Route::delete('/{id}', 'DriverController@destroy');
                Route::get('get_options', 'DriverController@getOptions');
            });

            Route::prefix('dispatchers')->group(function () {
                Route::get('/', 'DispatcherController@index')->name('tenant.dispatchers.index');
                Route::get('columns', 'DispatcherController@columns');
                Route::get('records', 'DispatcherController@records');
                Route::get('record/{id}', 'DispatcherController@record');
                Route::get('tables', 'DispatcherController@tables');
                Route::post('/', 'DispatcherController@store');
                Route::delete('/{id}', 'DispatcherController@destroy');
                Route::get('get_options', 'DispatcherController@getOptions');
            });

            Route::prefix('transports')->group(function () {
                Route::get('/', 'TransportController@index')->name('tenant.transports.index');
                Route::get('columns', 'TransportController@columns');
                Route::get('records', 'TransportController@records');
                Route::get('record/{id}', 'TransportController@record');
                Route::get('tables', 'TransportController@tables');
                Route::post('/', 'TransportController@store');
                Route::delete('/{id}', 'TransportController@destroy');
                Route::get('get_options', 'TransportController@getOptions');
            });

            Route::prefix('origin_addresses')->group(function () {
                Route::get('/', 'OriginAddressController@index')->name('tenant.origin_addresses.index');
                Route::get('columns', 'OriginAddressController@columns');
                Route::get('records', 'OriginAddressController@records');
                Route::get('record/{id}', 'OriginAddressController@record');
                Route::get('tables', 'OriginAddressController@tables');
                Route::post('/', 'OriginAddressController@store');
                Route::delete('/{id}', 'OriginAddressController@destroy');
                Route::get('get_options', 'OriginAddressController@getOptions');
            });

            Route::prefix('delivery_addresses')->group(function () {
                Route::get('tables', 'DeliveryAddressController@tables');
                Route::post('/', 'DeliveryAddressController@store');
                Route::get('get_options', 'DeliveryAddressController@getOptions');
            });

            Route::prefix('dispatch_persons')->group(function () {
                Route::get('tables', 'DispatchPersonController@tables');
                Route::post('/', 'DispatchPersonController@store');
                Route::get('get_options', 'DispatchPersonController@getOptions');
                Route::post('get_filter_options', 'DispatchPersonController@getFilterOptions');
            });

            Route::prefix('dispatch_addresses')->group(function () {

                Route::get('/', 'DispatchAddressController@index')->name('tenant.dispatch-addresses.index');
                Route::get('columns', 'DispatchAddressController@columns');
                Route::get('records', 'DispatchAddressController@records');
                Route::get('record/{id}', 'DispatchAddressController@record');

                Route::get('tables', 'DispatchAddressController@tables');
                Route::post('/', 'DispatchAddressController@store');
                Route::delete('/{id}', 'DispatchAddressController@destroy');
                Route::get('get_options/{sender_id}', 'DispatchAddressController@getOptions');
                Route::get('search/{person_id}', 'DispatchAddressController@searchAddress');
            });

        });
    });
}
