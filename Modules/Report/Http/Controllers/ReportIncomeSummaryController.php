<?php

namespace Modules\Report\Http\Controllers;

use Modules\Catalog\Models\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Report\Exports\DocumentExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportTrait;
use Modules\Establishment\Models\Establishment;
use Modules\User\Models\User;
use Modules\Document\Models\Document;
use Modules\Company\Models\Company;
use App\Models\Tenant\PaymentMethodType;
use Carbon\Carbon;
use Modules\Report\Http\Resources\CashCollection;
use Modules\Cash\Models\Cash;
use Modules\Company\Models\Configuration;


class ReportIncomeSummaryController extends Controller
{

    /**
     *
     * Usado en:
     * CashController - App
     *
     * @param  int $cash_id
     *
     */
    public function pdf($cash_id) {

        $company = Company::active();
        $cash = Cash::findOrFail($cash_id);
        $order_cash_income = Configuration::getOrderCashIncome();

        set_time_limit(0);
        $pdf = PDF::loadView('report::income_summary.report_pdf', compact("cash", "company", 'order_cash_income'));

        $filename = "Reporte_Resúmen_Ingreso - {$cash->user->name} - {$cash->date_opening} {$cash->time_opening}";

        return $pdf->download($filename.'.pdf');
    }


}
