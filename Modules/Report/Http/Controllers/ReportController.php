<?php

namespace Modules\Report\Http\Controllers;

use Carbon\Carbon;
use Modules\Catalog\Models\DocumentType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Document\Exports\DocumentExport;
use Modules\Document\Models\Document;
use Modules\Report\Traits\ReportTrait;
use Modules\Establishment\Models\Establishment;
use Modules\Company\Models\Company;

class ReportController extends Controller
{
    use ReportTrait;

    public function index() {
        $documentTypes = DocumentType::query()
            ->where('active', 1)
            ->get();

        $establishments = Establishment::all();

        return view('tenant.reports.index', compact('documentTypes','establishments'));
    }

    public function search(Request $request) {
        $documentTypes = DocumentType::all();
        $td = $this->getTypeDoc($request->document_type);
        $establishments = Establishment::all();

        $d = null;
        $a = null;
        $establishment = $request->establishment;
        $establishment_id = $this->getEstablishmentId($establishment);


        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;

            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('document_type_id', $td);
            }
        }
        else {
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest();
            } else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->where('document_type_id', $td);
            }
        }

        if(!is_null($establishment_id)){
            $reports = $reports->where('establishment_id', $establishment_id);
        }



        $reports = $reports->paginate(config('tenant.items_per_page'));

        // return json_encode($reports);

        return view("tenant.reports.index", compact("reports", "a", "d", "td", "documentTypes","establishment","establishments"));
    }



    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = Establishment::first();
        $td = $request->td;
        $establishment_id = $this->getEstablishmentId($request->establishment);

        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;

            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
            }
        }
        else {
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->get();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
            }
        }

        if(!is_null($establishment_id)){
            $reports = $reports->where('establishment_id', $establishment_id);
        }

        set_time_limit(0);

        $pdf = PDF::loadView('tenant.reports.report_pdf', compact("reports", "company", "establishment"));
        $filename = 'Reporte_Documentos'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }

    public function excel(Request $request) {
        $company = Company::first();
        $establishment = Establishment::first();
        $td= $request->td;
        $establishment_id = $this->getEstablishmentId($request->establishment);

        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;

            if (is_null($td)) {
                $records = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
            }
            else {
                $records = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
            }
        }
        else {
            if (is_null($td)) {
                $records = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->get();
            }
            else {
                $records = Document::with([ 'state_type', 'person'])
                    ->where('document_type_id', $td)
                    ->latest()
                    ->get();
            }
        }

        if(!is_null($establishment_id)){
            $records = $records->where('establishment_id', $establishment_id);
        }

        return (new DocumentExport)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->download('ReporteDoc'.Carbon::now().'.xlsx');
    }

    public function dataTablePerson($type, Request $request) {

        $persons = $this->getDataTablePerson($type, $request);

        return compact('persons');
    }


    public function dataTableItem(Request $request) {

        $items = $this->getDataTableItem($request);

        return compact('items');
    }
}
