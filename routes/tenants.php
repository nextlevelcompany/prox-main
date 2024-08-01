<?php

use App\Http\Controllers\Tenant\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Tenant\Auth\RegisteredUserController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Modules\Company\Http\Controllers\SettingController;
use App\Http\Controllers\Tenant\Api\ServiceController;
use App\Http\Controllers\Tenant\{
    SearchController,
    AccountController
};


Route::middleware('web')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/register', [RegisteredUserController::class, 'store']);
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });

    Route::get('search', [ SearchController::class, 'index'])->name('search.index');
    Route::get('buscar', [ SearchController::class, 'index'])->name('search.index');
    Route::get('search/tables', [ SearchController::class, 'tables']);
    Route::post('search', [ SearchController::class, 'store']);


    Route::get('/exchange_rate/ecommerce/{date}', [ServiceController::class, 'exchangeRateTest']);

    // Route::get('sale-notes/print/{external_id}/{format?}', 'Tenant\SaleNoteController@toPrint');
    Route::get('sale-notes/ticket/{id}/{format?}', 'Tenant\SaleNoteController@toTicket');
    // Route::get('purchases/print/{external_id}/{format?}', 'Tenant\PurchaseController@toPrint');
    // Route::get('quotations/print/{external_id}/{format?}', 'Tenant\QuotationController@toPrint');

    Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->group(function () {
        // Route::get('catalogs', 'Tenant\CatalogController@index')->name('tenant.catalogs.index');







        //Card Brands


        //ChargeDiscounts
        Route::get('charge_discounts', 'Tenant\ChargeDiscountController@index')->name('tenant.charge_discounts.index');
        Route::get('charge_discounts/records/{type}', 'Tenant\ChargeDiscountController@records');
        Route::get('charge_discounts/create', 'Tenant\ChargeDiscountController@create');
        Route::get('charge_discounts/tables/{type}', 'Tenant\ChargeDiscountController@tables');
        Route::get('charge_discounts/record/{charge}', 'Tenant\ChargeDiscountController@record');
        Route::post('charge_discounts', 'Tenant\ChargeDiscountController@store');
        Route::delete('charge_discounts/{charge}', 'Tenant\ChargeDiscountController@destroy');


        // Route::get('customers/list', 'Tenant\PersonController@clientsForGenerateCPE');


        // apiperu no usa estas rutas - revisar
        Route::get('services/ruc/{number}', 'Tenant\Api\ServiceController@ruc');
        Route::get('services/dni/{number}', 'Tenant\Api\ServiceController@dni');
        Route::post('services/exchange_rate', 'Tenant\Api\ServiceController@exchange_rate');
        Route::post('services/search_exchange_rate', 'Tenant\Api\ServiceController@searchExchangeRateByDate');
        Route::get('services/exchange_rate/{date}', 'Tenant\Api\ServiceController@exchangeRateTest');

        //BUSQUEDA DE DOCUMENTOS
        // Route::get('busqueda', 'Tenant\SearchController@index')->name('search');
        // Route::post('busqueda', 'Tenant\SearchController@index')->name('search');

        //Codes
        Route::get('codes/records', 'Tenant\Catalogs\CodeController@records');
        Route::get('codes/tables', 'Tenant\Catalogs\CodeController@tables');
        Route::get('codes/record/{code}', 'Tenant\Catalogs\CodeController@record');
        Route::post('codes', 'Tenant\Catalogs\CodeController@store');
        Route::delete('codes/{code}', 'Tenant\Catalogs\CodeController@destroy');



        //Banks


        //Exchange Rates
        Route::get('exchange_rates/records', 'Tenant\ExchangeRateController@records');
        Route::post('exchange_rates', 'Tenant\ExchangeRateController@store');


        //Cuenta
        Route::get('cuenta/payment_index', [ AccountController::class, 'paymentIndex'])->name('tenant.payment.index');
        Route::get('cuenta/configuration', [ AccountController::class, 'index'])->name('tenant.configuration.index');
        Route::get('cuenta/payment_records', [ AccountController::class, 'paymentRecords']);
        Route::get('cuenta/tables', [ AccountController::class, 'tables']);
        Route::post('cuenta/update_plan', [ AccountController::class, 'updatePlan']);
        Route::post('cuenta/payment_culqui', [ AccountController::class, 'paymentCulqui'])->name('tenant.account.payment_culqui');

        //Payment Methods


        //formats PDF
        Route::get('templates', 'Tenant\FormatTemplateController@records');
        // Configuración del Login


        // Route::post('extra_info/items', 'ExtraInfoController@getExtraDataForItems');

        //Almacen de columnas por usuario
        // Route::post('validate_columns', 'Tenant\SettingController@getColumnsToDatatable');
        // Route::post('general-upload-temp-image', 'Controller@generalUploadTempImage');
        Route::get('general-get-current-warehouse', 'Controller@generalGetCurrentWarehouse');

        Route::post('general-upload-temp-image', [Controller::class, 'generalUploadTempImage']);
        Route::post('validate_columns', [ SettingController::class, 'getColumnsToDatatable']);

    });
});
