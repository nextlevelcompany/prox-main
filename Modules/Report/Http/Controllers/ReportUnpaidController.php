<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Report\Exports\NoPaidExport;
use Illuminate\Http\Request;
use Modules\Company\Models\Company;
use Carbon\Carbon;
use Modules\Dashboard\Helpers\DashboardView;
use Modules\Finance\Traits\UnpaidTrait;

class ReportUnpaidController extends Controller
{

    use UnpaidTrait;

    public function excel(Request $request)
    {

        $records = (new DashboardView())->getUnpaidFilterUser($request->all())->get();
        $records = $this->transformRecords($records);

        $company = Company::first();
        $noPaidExport = new NoPaidExport();
        $noPaidExport
            ->company($company)
            ->records($records);
        return $noPaidExport->download('Reporte_Cuentas_Por_Cobrar' . Carbon::now() . '.xlsx');

    }


}
