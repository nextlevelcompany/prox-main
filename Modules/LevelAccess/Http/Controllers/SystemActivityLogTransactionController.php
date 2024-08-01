<?php

namespace Modules\LevelAccess\Http\Controllers;

use Illuminate\Http\Request;
use Modules\LevelAccess\Http\Resources\SystemActivityTransactionCollection;
use App\Traits\ElectronicDocumentTrait;
use App\Http\Controllers\Controller;
use Modules\LevelAccess\Exports\GeneralFormatExport;

class SystemActivityLogTransactionController extends Controller
{
    use ElectronicDocumentTrait;

    public function index()
    {
        return view('tenant.level_access.system_activity_logs.transactions.index');
    }

    public function columns()
    {
        return [
            'date_of_issue' => 'Fecha emisión',
            'time_of_issue' => 'Hora emisión',
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);

        return new SystemActivityTransactionCollection($records->paginate(config('tenant.items_per_page')));
    }

    private function getRecords($request)
    {
        $documents = $this->getQuerySystemActivityLogTransaction('documents', $request);
        $dispatches = $this->getQuerySystemActivityLogTransaction('dispatches', $request);
        $perceptions = $this->getQuerySystemActivityLogTransaction('perceptions', $request);
        $purchase_settlements = $this->getQuerySystemActivityLogTransaction('purchase_settlements', $request);
        $retentions = $this->getQuerySystemActivityLogTransaction('retentions', $request);

        $summaries = $this->getQuerySystemActivityLogTransactionGroup('summaries', 'RC', $request);
        $summary_voided = $this->getQuerySystemActivityLogTransactionGroup('summaries', 'RC', $request, true);
        $voided = $this->getQuerySystemActivityLogTransactionGroup('voided', 'RA', $request);


        $records = $documents->union($dispatches)
                            ->union($perceptions)->union($purchase_settlements)
                            ->union($retentions)->union($summaries)
                            ->union($summary_voided)->union($voided);


        return $records->orderBy('date_of_issue', 'desc')->orderBy('time_of_issue', 'desc');
    }


    /**
     *
     * @param  string $type
     * @param  Request $request
     * @return mixed
     */
    public function exportReport($type, Request $request)
    {
        if($type === 'excel')
        {
            $records = $this->getRecords($request)->get();

            $header_data = $this->generalDataForHeaderReport();

            $data = [
                'company' => $header_data['company'],
                'records' => $records,
            ];

            $general_format_export = new GeneralFormatExport();
            $general_format_export->view_name("tenant.level_access.system_activity_logs.reports.transactions_{$type}")->data($data);

            return $general_format_export->download($this->generalFilenameReport('Reporte_Actividades_Sistema_Transacciones', 'xlsx'));

        }

        return $this->generalResponse(false, 'Formato no permitido');
    }



}
