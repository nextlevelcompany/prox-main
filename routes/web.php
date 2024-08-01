<?php

use App\Http\Controllers\System\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\AccountingController;
use Modules\System\Http\Controllers\AccountStatusController;
use Modules\System\Http\Controllers\BackupController;
use Modules\System\Http\Controllers\CertificateController;
use Modules\System\Http\Controllers\ClientController;
use Modules\System\Http\Controllers\ClientPaymentController;
use Modules\System\Http\Controllers\ConfigurationController;
use Modules\System\Http\Controllers\HomeController;
use Modules\System\Http\Controllers\PlanController;
use Modules\System\Http\Controllers\ServiceController;
use Modules\System\Http\Controllers\StatusController;
use Modules\System\Http\Controllers\UpdateController;
use Modules\System\Http\Controllers\UserController;

$prefix = env('PREFIX_URL', null);
$prefix = !empty($prefix) ? $prefix . "." : '';
// $app_url = $prefix . env('APP_URL_BASE');
$app_url = $prefix . config('tenant.app_url_base');


Route::domain($app_url)->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
        Route::get('/', function () {
            return redirect()->route('system.dashboard');
        });

        Route::get('dashboard', [HomeController::class, 'index'])->name('system.dashboard');

        Route::prefix('clients')->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('system.clients.index');
            Route::get('records', [ClientController::class, 'records']);
            Route::get('record/{client}', [ClientController::class, 'record']);
            Route::get('create', [ClientController::class, 'create']);
            Route::get('tables', [ClientController::class, 'tables']);
            Route::get('charts', [ClientController::class, 'charts']);
            Route::post('/', [ClientController::class, 'store']);
            Route::post('update', [ClientController::class, 'update']);
            // Route::delete('{client}', [ClientController::class, 'destroy']);
            Route::delete('{client}/{input_validate}', [ClientController::class, 'destroy']);

            Route::post('password/{client}', [ClientController::class, 'password']);
            Route::post('locked_emission', [ClientController::class, 'lockedEmission']);
            Route::post('locked_tenant', [ClientController::class, 'lockedTenant']);
            Route::post('locked_user', [ClientController::class, 'lockedUser']);
            Route::post('renew_plan', [ClientController::class, 'renewPlan']);
            Route::post('set_billing_cycle', [ClientController::class, 'startBillingCycle']);
            Route::post('upload', [ClientController::class, 'upload']);

            Route::post('locked-by-column', [ClientController::class, 'lockedByColumn']);

        });

        Route::prefix('client_payments')->group(function () {
            Route::get('records/{client_id}', [ClientPaymentController::class, 'records']);
            Route::get('client/{client_id}', [ClientPaymentController::class, 'client']);
            Route::get('tables', [ClientPaymentController::class, 'tables']);
            Route::post('/', [ClientPaymentController::class, 'store']);
            Route::delete('{client_payment}', [ClientPaymentController::class, 'destroy']);
            Route::get('cancel_payment/{client_payment_id}', [ClientPaymentController::class, 'cancel_payment']);
        });

        Route::prefix('client_account_status')->group(function () {
            Route::get('records/{client_id}', [AccountStatusController::class, 'records']);
            Route::get('client/{client_id}', [AccountStatusController::class, 'client']);
            Route::get('tables', [AccountStatusController::class, 'tables']);
        });

        Route::prefix('plans')->group(function () {
            Route::get('/', [PlanController::class, 'index'])->name('system.plans.index');
            Route::get('records', [PlanController::class, 'records']);
            Route::get('tables', [PlanController::class, 'tables']);
            Route::get('record/{plan}', [PlanController::class, 'record']);
            Route::post('/', [PlanController::class, 'store']);
            Route::delete('{plan}', [PlanController::class, 'destroy']);
        });

        Route::prefix('accounting')->group(function () {
            Route::get('', [AccountingController::class, 'index'])->name('system.accounting.index');
            Route::get('records', [AccountingController::class, 'records']);
            Route::get('download', [AccountingController::class, 'download']);
        });

        Route::prefix('users')->group(function () {
            Route::get('create', [UserController::class, 'create'])->name('system.users.create');
            Route::get('record', [UserController::class, 'record']);
            Route::post('/', [UserController::class, 'store']);
        });

        Route::prefix('certificates')->group(function () {
            Route::get('record', [CertificateController::class, 'record']);
            Route::post('uploads', [CertificateController::class, 'uploadFile']);
            Route::post('saveSoapUser', [CertificateController::class, 'saveSoapUser']);
            Route::delete('/', [CertificateController::class, 'destroy']);
        });

        Route::prefix('configurations')->group(function () {
            Route::get('/', [ConfigurationController::class, 'index'])->name('system.configuration.index');
            Route::post('login', [ConfigurationController::class, 'storeLoginSettings']);
            Route::post('bg', [ConfigurationController::class, 'storeBgLogin']);
            Route::post('/', [ConfigurationController::class, 'store']);
            Route::get('record', [ConfigurationController::class, 'record']);
            Route::get('apiruc', [ConfigurationController::class, 'apiruc']);
            Route::get('apkurl', [ConfigurationController::class, 'apkurl']);
            Route::get('information', [ConfigurationController::class, 'InfoIndex'])->name('system.information');
            Route::post('update-tenant-discount-type-base', [ConfigurationController::class, 'updateTenantDiscountTypeBase']);
            Route::post('other-configuration', [ConfigurationController::class, 'storeOtherConfiguration']);
            Route::get('get-other-configuration', [ConfigurationController::class, 'getOtherConfiguration']);
            Route::post('upload-tenant-ads', [ConfigurationController::class, 'uploadTenantAds']);
            Route::post('visual', [ConfigurationController::class, 'storeVisualSettings']);
        });

        Route::prefix('companies')->group(function () {
            Route::get('record', [CertificateController::class, 'record']);
            Route::post('/', [CertificateController::class, 'store']);
        });

        // auto-update
        Route::get('changelog', [UpdateController::class, 'index'])->name('system.update');
        Route::get('changelog/list', [UpdateController::class, 'changelog'])->name('system.changelog');
        Route::get('changelog/version', [UpdateController::class, 'version'])->name('system.update.version');
        Route::get('changelog/branch', [UpdateController::class, 'branch'])->name('system.update.branch');

        //Configuration
        // Route::get('information', 'System\ConfigurationController@InfoIndex')->name('system.information');
        Route::prefix('status')->group(function () {
            Route::get('history',  [StatusController::class, 'history']);
            Route::get('memory',  [StatusController::class, 'memory']);
            Route::get('cpu',  [StatusController::class, 'cpu']);
            // Route::get('configurations/apiruc', 'System\ConfigurationController@apiruc');
            // Route::get('configurations/apkurl', 'System\ConfigurationController@apkurl');
        });

        Route::prefix('backup')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('system.backup');
            Route::post('db', [BackupController::class, 'db'])->name('system.backup.db');
            Route::post('files', [BackupController::class, 'files'])->name('system.backup.files');
            Route::post('upload', [BackupController::class, 'upload'])->name('system.backup.upload');
            Route::get('last-backup', [BackupController::class, 'mostRecent']);
            Route::get('download/{filename}', [BackupController::class, 'download']);
        });

        Route::get('service/ruc/{number}', [ServiceController::class, 'ruc']);
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    });
});
