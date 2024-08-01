<?php

use Illuminate\Support\Facades\Route;
use Modules\Document\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Tenant\Api\MobileController;
use Modules\Quotation\Http\Controllers\Api\QuotationController;
use Modules\Purchase\Http\Controllers\Api\PurchaseSettlementController;
use Modules\SaleNote\Http\Controllers\Api\SaleNoteController;
use Modules\Voided\Http\Controllers\Api\VoidedController;
use Modules\Summary\Http\Controllers\Api\SummaryController;
use Modules\Perception\Http\Controllers\Api\PerceptionController;
use Modules\Retention\Http\Controllers\Api\RetentionController;
use Modules\Dispatch\Http\Controllers\Api\DispatchController;
use App\Http\Controllers\Tenant\Api\ServiceController;
use Modules\Document\Http\Controllers\DocumentController as DocumentControllerWeb;
use Modules\Company\Http\Controllers\ConfigurationController;
use Modules\Order\Http\Controllers\Api\OrderController;
use Modules\Company\Http\Controllers\Api\CompanyController;


// Route::get('generate_token', [MobileController::class, 'getSeries']);

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) {
    Route::domain($hostname->fqdn)->group(function () {

        Route::post('login', [MobileController::class, 'login']);

        Route::middleware(['auth:api', 'locked.tenant'])->group(function () {

            //MOBILE
            Route::controller(MobileController::class)->group(function () {

                Route::get('document/search-items', 'searchItems');
                Route::post('items/{id}/update', 'updateItem');
                Route::post('item', 'item');
                Route::get('document/customers', 'customers');
                Route::get('document/search-customers', 'searchCustomers');
                Route::get('document/tables', 'tables');
                Route::get('document/series', 'getSeries');
                Route::get('document/paymentmethod', 'getPaymentmethod');
                Route::post('document/email', 'document_email');
                Route::post('person', 'person');
                Route::post('item/upload', 'upload');
                Route::get('report', 'report');

            });


            Route::controller(SaleNoteController::class)->group(function () {

                Route::post('sale-note/{id}/generate-cpe', 'generateCPE');
                Route::post('sale-note', 'store');
                Route::get('sale-note/series', 'series');
                Route::get('sale-note/lists', 'lists');
                Route::post('sale-note/email', 'email');

            });


            Route::controller(DocumentController::class)->group(function () {

                Route::get('documents/lists/{startDate}/{endDate}', 'lists');
                Route::get('documents/lists', 'lists');
                Route::post('documents/updatedocumentstatus', 'updatestatus');
                Route::post('documents', 'store');
                Route::post('documents/send', 'send');
                Route::post('documents_server', 'storeServer');
                Route::get('document_check_server/{external_id}', 'documentCheckServer');

            });


            Route::controller(VoidedController::class)->group(function () {
                Route::post('voided', 'store');
                Route::post('voided/status', 'status');
            });


            Route::controller(SummaryController::class)->group(function () {
                Route::post('summaries', 'store');
                Route::post('summaries/status', 'status');
            });


            Route::controller(RetentionController::class)->group(function () {
                Route::post('retentions', 'store');
            });


            Route::controller(DispatchController::class)->group(function () {

                Route::post('dispatches', 'store');
                Route::post('dispatches/send', 'send');
                Route::post('dispatches/status_ticket', 'statusTicket');

            });


            Route::controller(ServiceController::class)->group(function () {

                Route::get('services/ruc/{number}', 'ruc');
                // Route::get('services/dni/{number}', 'dni');
                Route::post('services/consult_cdr_status', 'consultCdrStatus');
                // Route::post('services/validate_cpe', 'validateCpe');
                Route::post('documents/status', 'documentStatus');

            });


            Route::controller(PerceptionController::class)->group(function () {
                Route::post('perceptions', 'store');
            });



            //liquidacion de compra
            Route::controller(PurchaseSettlementController::class)->group(function () {
                Route::post('purchase-settlements', 'store');
            });


            //Pedidos
            Route::controller(OrderController::class)->group(function () {
                Route::get('orders', 'records');
                Route::post('orders', 'store');
            });


            //Company
            Route::get('company', [CompanyController::class, 'record']);


            // Cotizaciones
            Route::controller(QuotationController::class)->group(function () {

                Route::post('quotations', 'store');
                Route::get('quotations/list', 'list');
                Route::post('quotations/email', 'email');

            });

        });


        Route::controller(DocumentControllerWeb::class)->group(function () {
            Route::get('documents/search/customers', 'searchCustomers');
            Route::get('sendserver/{document_id}/{query?}', 'sendServer');
        });


        Route::post('configurations/generateDispatch', [ConfigurationController::class, 'generateDispatch']);

    });
} else {
    Route::domain(env('APP_URL_BASE'))->group(function () {


        Route::middleware(['auth:system_api'])->group(function () {

            //reseller
            Route::post('reseller/detail', 'Modules\System\Http\Controllers\Api\ResellerController@resellerDetail');
            // Route::post('reseller/lockedAdmin', 'System\Api\ResellerController@lockedAdmin');
            // Route::post('reseller/lockedTenant', 'System\Api\ResellerController@lockedTenant');

            Route::get('restaurant/partner/list', 'Modules\System\Http\Controllers\Api\RestaurantPartnerController@list');
            Route::post('restaurant/partner/store', 'Modules\System\Http\Controllers\Api\RestaurantPartnerController@store');
            Route::post('restaurant/partner/search', 'Modules\System\Http\Controllers\Api\RestaurantPartnerController@search');

        });

    });

}
