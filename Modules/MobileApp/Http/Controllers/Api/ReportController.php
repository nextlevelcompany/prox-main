<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Dashboard\Helpers\DashboardData;
use App\CoreFacturalo\Helpers\Functions\FunctionsHelper;
use Modules\MobileApp\Http\Requests\Api\ReportGeneralSaleRequest;
use Modules\Establishment\Models\Establishment;

class ReportController extends Controller
{
    public function filters()
    {
        $establishments = Establishment::filterDataForTables()->get();

        return compact('establishments');
    }


    /**
     *
     * Reporte general de ventas
     * Totales incluye pedidos, notas de venta, cpe
     * Gráfico incluye notas de venta, cpe
     *
     * @param  ReportGeneralSaleRequest $request
     * @return array
     */
    public function reportGeneralSale(ReportGeneralSaleRequest $request)
    {

        $establishment_id = $request['establishment_id'] ?? auth()->user()->establishment_id;
        $period = $request['period'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];
        $d_start = null;
        $d_end = null;

        FunctionsHelper::setDateInPeriod($request, $d_start, $d_end);

        return [
            'data' => (new DashboardData())->getGeneralTotals($establishment_id, $d_start, $d_end, $period, $month_start, $month_end)
        ];

    }


}
