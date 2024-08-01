<?php

use Illuminate\Support\Facades\Route;

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            Route::get('/services', 'ItemController@indexServices')->name('tenant.services')->middleware('redirect.level');
            Route::get('/items_ecommerce', 'ItemController@index_ecommerce')->name('tenant.items_ecommerce.index');
            Route::post('/get-items', 'ItemController@getAllItems');

            Route::post('/extra_info/items', 'ExtraInfoController@getExtraDataForItems');

            Route::prefix('items')->group(function () {
                Route::get('/', 'ItemController@index')->name('tenant.items.index')->middleware('redirect.level');
                Route::get('/columns', 'ItemController@columns');
                Route::get('/records', 'ItemController@records');
                Route::get('/tables', 'ItemController@tables');
                Route::get('/record/{item}', 'ItemController@record');
                Route::post('/', 'ItemController@store');
                Route::delete('/{item}', 'ItemController@destroy');
                Route::delete('/item-unit-type/{item}', 'ItemController@destroyItemUnitType');
                Route::post('/import', 'ItemController@import');
                Route::post('/catalog', 'ItemController@catalog');
                Route::get('/import/tables', 'ItemController@tablesImport');
                Route::post('/upload', 'ItemController@upload');
                Route::post('/visible_store', 'ItemController@visibleStore');
                Route::post('/duplicate', 'ItemController@duplicate');
                Route::get('/disable/{item}', 'ItemController@disable');
                Route::get('/enable/{item}', 'ItemController@enable');
                Route::get('/images/{item}', 'ItemController@images');
                Route::get('/images/delete/{id}', 'ItemController@delete_images');
                Route::get('/export', 'ItemController@export')->name('tenant.items.export');
                Route::get('/export/wp', 'ItemController@exportWp')->name('tenant.items.export.wp');
                Route::get('/export/digemid', 'ItemController@exportDigemid');
                Route::get('/search-items', 'ItemController@searchItems');
                Route::get('/search/item/{item}', 'ItemController@searchItemById');
                Route::get('/item/tables', 'ItemController@item_tables');
                Route::get('/export/barcode', 'ItemController@exportBarCode')->name('tenant.items.export.barcode');
                Route::get('/export/extra_atrributes/PDF', 'ItemController@downloadExtraDataPdf');
                Route::get('/export/extra_atrributes/XLSX', 'ItemController@downloadExtraDataItemsExcel');
                Route::get('/export/barcode_full', 'ItemController@exportBarCodeFull');
                Route::get('/export/barcode/print', 'ItemController@printBarCode')->name('tenant.items.export.barcode.print');
                Route::get('/export/barcode/print_x', 'ItemController@printBarCodeX')->name('tenant.items.export.barcode.print.x');
                Route::get('/export/barcode/last', 'ItemController@itemLast')->name('tenant.items.last');
                Route::get('/barcode/{item}', 'ItemController@generateBarcode');
                Route::post('/import/item-price-lists', 'ItemController@importItemPriceLists');
                Route::post('/import/item-with-extra-data', 'ItemController@importItemWithExtraData');
                Route::get('/data-history/{item}', 'ItemController@getDataHistory');
                Route::get('/available-series/records', 'ItemController@availableSeriesRecords');
                Route::get('/history-sales/records', 'ItemController@itemHistorySales');
                Route::get('/history-purchases/records', 'ItemController@itemHistoryPurchases');
                Route::get('/last-sale', 'ItemController@itemtLastSale');
                Route::post('/import/item-sets', 'ItemSetController@importItemSets');
                Route::post('/import/item-sets-individual', 'ItemSetController@importItemSetsIndividual');
                Route::post('/import/items-update-prices', 'ItemController@importItemUpdatePrices');
            });

            Route::prefix('categories')->group(function () {
                Route::get('/', 'CategoryController@index')->name('tenant.categories.index')->middleware('redirect.level');
                Route::get('/records', 'CategoryController@records');
                Route::get('/columns', 'CategoryController@columns');
                Route::get('/record/{category}', 'CategoryController@record');
                Route::post('/', 'CategoryController@store');
                Route::delete('/{category}', 'CategoryController@destroy');
            });

            Route::prefix('brands')->group(function () {
                Route::get('/', 'BrandController@index')->name('tenant.brands.index')->middleware('redirect.level');
                Route::get('/records', 'BrandController@records');
                Route::get('/record/{brand}', 'BrandController@record');
                Route::post('/', 'BrandController@store');
                Route::get('/columns', 'BrandController@columns');
                Route::delete('/{brand}', 'BrandController@destroy');
            });

            Route::prefix('zones')->group(function () {
                Route::get('/', 'ZoneController@index')->name('tenant.zone.index');
                Route::post('/', 'ZoneController@store');
                Route::get('/records', 'ZoneController@records');
                Route::get('/record/{brand}', 'ZoneController@record');
                Route::get('/columns', 'ZoneController@columns');
                Route::delete('/{brand}', 'ZoneController@destroy');
            });

            Route::prefix('incentives')->group(function () {
                Route::get('/', 'IncentiveController@index')->name('tenant.incentives.index')->middleware('redirect.level');
                Route::get('/records', 'IncentiveController@records');
                Route::get('/record/{incentive}', 'IncentiveController@record');
                Route::post('/', 'IncentiveController@store');
                Route::get('/columns', 'IncentiveController@columns');
                Route::delete('/{incentive}', 'IncentiveController@destroy');
            });

            Route::prefix('item-lots')->group(function () {
                Route::get('/', 'ItemLotController@index')->name('tenant.item-lots.index');
                Route::get('/records', 'ItemLotController@records');
                Route::get('/record/{record}', 'ItemLotController@record');
                Route::post('/', 'ItemLotController@store');
                Route::get('/columns', 'ItemLotController@columns');
                Route::get('/export', 'ItemLotController@export');
            });

            Route::prefix('web-platforms')->group(function () {
                Route::get('/', 'WebPlatformController@index');
                Route::get('/records', 'WebPlatformController@records');
                Route::get('/record/{brand}', 'WebPlatformController@record');
                Route::post('/', 'WebPlatformController@store');
                Route::delete('/{record}', 'WebPlatformController@destroy');
            });

            Route::prefix('item-lots-group')->group(function () {
                Route::get('available-data/{item_id}', 'ItemLotsGroupController@getAvailableItemLotsGroup');
            });

            Route::prefix('item-sets')->group(function () {
                Route::get('/', 'ItemSetController@index')->name('tenant.item_sets.index')->middleware('redirect.level');
                Route::get('/columns', 'ItemSetController@columns');
                Route::get('/records', 'ItemSetController@records');
                Route::get('/tables', 'ItemSetController@tables');
                Route::get('/record/{item}', 'ItemSetController@record');
                Route::post('/', 'ItemSetController@store');
                Route::delete('/{item}', 'ItemSetController@destroy');
                Route::delete('/item-unit-type/{item}', 'ItemSetController@destroyItemUnitType');
                Route::post('/import', 'ItemSetController@import');
                Route::post('/upload', 'ItemSetController@upload');
                Route::post('/visible_store', 'ItemSetController@visibleStore');
                Route::get('/item/tables', 'ItemSetController@item_tables');
            });

            Route::prefix('tags')->group(function () {
                Route::get('/', 'TagController@index')->name('tenant.tags.index');
                Route::get('/columns', 'TagController@columns');
                Route::get('/records', 'TagController@records');
                Route::get('/record/{tag}', 'TagController@record');
                Route::post('/', 'TagController@store');
                Route::delete('/{tag}', 'TagController@destroy');
            });

            Route::prefix('promotions')->group(function () {
                Route::get('/', 'PromotionController@index')->name('tenant.promotion.index');
                Route::get('/columns', 'PromotionController@columns');
                Route::get('/tables', 'PromotionController@tables');
                Route::get('/records', 'PromotionController@records');
                Route::get('/record/{tag}', 'PromotionController@record');
                Route::post('/', 'PromotionController@store');
                Route::delete('/{promotion}', 'PromotionController@destroy');
                Route::post('/upload', 'PromotionController@upload');
            });
        });
    });
}
