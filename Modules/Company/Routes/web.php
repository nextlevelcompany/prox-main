<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {
            Route::prefix('companies')->group(function () {
                //Company
                Route::get('/create', 'CompanyController@create')->name('tenant.companies.create')->middleware('redirect.level');
                Route::get('/tables', 'CompanyController@tables');
                Route::get('/record', 'CompanyController@record');
                Route::post('', 'CompanyController@store');
                Route::post('/uploads', 'CompanyController@uploadFile');

                //configuracion envio documento a pse
                Route::post('/store-send-pse', 'CompanyController@storeSendPse');
                Route::get('/record-send-pse', 'CompanyController@recordSendPse');

                //configuracion WhatsApp Api
                Route::post('/store-whatsapp-api', 'CompanyController@storeWhatsAppApi');
                Route::get('/record-whatsapp-api', 'CompanyController@recordWhatsAppApi');
            });

            Route::prefix('certificates')->group(function () {
                Route::get('/record', 'CertificateController@record');
                Route::post('/uploads', 'CertificateController@uploadFile');
                Route::delete('/', 'CertificateController@destroy');
            });

            Route::get('list-reports', 'SettingController@listReports');
            Route::get('list-extras', 'SettingController@listExtras');
            Route::get('list-settings', 'SettingController@indexSettings')->name('tenant.general_configuration.index');
            Route::get('list-banks', 'SettingController@listBanks');
            Route::get('list-bank-accounts', 'SettingController@listAccountBanks');
            Route::get('list-currencies', 'SettingController@listCurrencies');
            Route::get('list-cards', 'SettingController@listCards');
            Route::get('list-platforms', 'SettingController@listPlatforms');
            Route::get('list-attributes', 'SettingController@listAttributes');
            Route::get('list-detractions', 'SettingController@listDetractions');
            Route::get('list-units', 'SettingController@listUnits');
            Route::get('list-payment-methods', 'SettingController@listPaymentMethods');
            Route::get('list-incomes', 'SettingController@listIncomes');
            Route::get('list-payments', 'SettingController@listPayments');
            Route::get('list-vouchers-type', 'SettingController@listVouchersType');
            Route::get('list-transfer-reason-types', 'SettingController@listTransferReasonTypes');

            Route::get('advanced', 'AdvancedController@index')->name('tenant.advanced.index')->middleware('redirect.level');

            Route::prefix('configurations')->group(function () {
                Route::get('/sale-notes', 'SaleNoteController@SetAdvanceConfiguration')->name('tenant.sale_notes.configuration')->middleware('redirect.level');
                Route::post('/sale-notes', 'SaleNoteController@SaveSetAdvanceConfiguration');
                Route::get('/addSeeder', 'ConfigurationController@addSeeder');
                Route::get('/preprinted/addSeeder', 'ConfigurationController@addPreprintedSeeder');
                Route::get('/getFormats', 'ConfigurationController@getFormats');
                Route::get('/preprinted/getFormats', 'ConfigurationController@getPreprintedFormats');
                Route::get('/create', 'ConfigurationController@create')->name('tenant.configurations.create');
                Route::get('/record', 'ConfigurationController@record');
                Route::post('/', 'ConfigurationController@store');
                Route::post('/apiruc', 'ConfigurationController@storeApiRuc');
                Route::post('/icbper', 'ConfigurationController@icbper');
                Route::post('/changeFormat', 'ConfigurationController@changeFormat');
                Route::get('/tables', 'ConfigurationController@tables');
                Route::get('/visual_defaults', 'ConfigurationController@visualDefaults')->name('visual_defaults');
                Route::get('/visual/get_menu', 'ConfigurationController@visualGetMenu')->name('visual_get_menu');
                Route::post('/visual/set_menu', 'ConfigurationController@visualSetMenu')->name('visual_set_menu');
                Route::post('/visual_settings', 'ConfigurationController@visualSettings')->name('visual-settings');
                Route::post('/visual/upload_skin', 'ConfigurationController@visualUploadSkin')->name('visual_upload_skin');
                Route::post('/visual/delete_skin', 'ConfigurationController@visualDeleteSkin')->name('visual_delete_skin');
                Route::get('/pdf_templates', 'ConfigurationController@pdfTemplates')->name('tenant.advanced.pdf_templates');
                Route::get('/pdf_guide_templates', 'ConfigurationController@pdfGuideTemplates')->name('tenant.advanced.pdf_guide_templates');
                Route::get('/pdf_preprinted_templates', 'ConfigurationController@pdfPreprintedTemplates')->name('tenant.advanced.pdf_preprinted_templates');
                Route::post('/uploads', 'ConfigurationController@uploadFile');
                Route::post('/preprinted/generateDispatch', 'ConfigurationController@generateDispatch');
                Route::get('/preprinted/{template}', 'ConfigurationController@show');
                Route::get('/change-mode', 'ConfigurationController@changeMode')->name('settings.change_mode');

                Route::get('/templates/ticket/refresh', 'ConfigurationController@refreshTickets');
                Route::get('/pdf_templates/ticket', 'ConfigurationController@pdfTicketTemplates')->name('tenant.advanced.pdf_ticket_templates');
                Route::get('/templates/ticket/records', 'ConfigurationController@getTicketFormats');
                Route::post('/templates/ticket/update', 'ConfigurationController@changeTicketFormat');
                Route::get('/apiruc', 'ConfigurationController@apiruc');

                Route::post('/pdf-footer-images', 'ConfigurationController@pdfFooterImages');
                Route::get('/get-pdf-footer-images', 'ConfigurationController@getPdfFooterImages');
            });

            Route::prefix('tasks')->group(function () {
                Route::get('/', 'TaskController@index')->name('tenant.tasks.index')->middleware('redirect.level');
                Route::post('/commands', 'TaskController@listsCommand');
                Route::post('/tables', 'TaskController@tables');
                Route::post('/', 'TaskController@store');
                Route::delete('/{task}', 'TaskController@destroy');
            });

            Route::prefix('login-page')->group(function () {
                Route::get('/', 'LoginConfigurationController@index')->name('tenant.login_page')->middleware('redirect.level');
                Route::post('/upload-bg-image', 'LoginConfigurationController@uploadBgImage');
                Route::post('/update', 'LoginConfigurationController@update');
            });

            Route::post('options/delete_documents', 'OptionController@deleteDocuments');
        });
    });
}
