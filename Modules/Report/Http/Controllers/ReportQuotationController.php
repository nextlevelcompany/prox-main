<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Report\Exports\QuotationExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportTrait;
use Modules\Establishment\Models\Establishment;
use Modules\Quotation\Models\Quotation;
use Modules\Company\Models\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\QuotationCollection;

class ReportQuotationController extends Controller
{
    use ReportTrait;

    public function index()
    {
        return view('tenant.report.quotations.index');
    }

    public function search(Request $request)
    {
        // return 'asd';
        $d = null;
        $a = null;


        if ($request->has('d') && $request->has('a')) {

            //return 'entra';
            $d = $request->d;
            $a = $request->a;

            $reports = Quotation::whereBetween('date_of_issue', [$d, $a])->latest();
        } else {

            $reports = Quotation::latest();
        }

        $reports = $reports->paginate(config('tenant.items_per_page'));

        //$reports = new QuotationCollection($source->paginate(config('tenant.items_per_page')));
        //return json_encode($reports);

        return view('tenant.reports.quotations.index', compact('reports', 'a', 'd'));
    }

    public function pdf2(Request $request)
    {
        $company = Company::first();
        // $establishment = Establishment::first();
        // $td = $request->td;
        // $establishment_id = $this->getEstablishmentId($request->establishment);

        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;

            $reports = Quotation::whereBetween('date_of_issue', [$d, $a])->latest()->get();

            /* if (is_null($td)) {
                 $reports = Purchase::with([ 'state_type', 'supplier'])
                     ->whereBetween('date_of_issue', [$d, p[pppppp-$a])
                     ->latest()
                     ->get();
             }
             else {
                 $reports = Purchase::with([ 'state_type', 'supplier'])
                     ->whereBetween('date_of_issue', [$d, $a])
                     ->latest()
                     ->where('document_type_id', $td)
                     ->get();
             }*/
        } else {

            $reports = Quotation::latest()->get();
            /* if (is_null($td)) {
                 $reports = Purchase::with([ 'state_type', 'supplier'])
                     ->latest()
                     ->get();
             }
             else {
                 $reports = Purchase::with([ 'state_type', 'supplier'])
                     ->latest()
                     ->where('document_type_id', $td)
                     ->get();
             }*/
        }

        /*if(!is_null($establishment_id)){
            $reports = $reports->where('establishment_id', $establishment_id);
        }*/

        $pdf = PDF::loadView('tenant.reports.quotations.report_pdf', compact("reports", "company"));
        $filename = 'Reporte_Cotizacion' . date('YmdHis');

        return $pdf->download($filename . '.pdf');
    }

    public function excel2(Request $request)
    {
        $company = Company::first();
        // $establishment = Establishment::first();
        //  $td= $request->td;
        //  $establishment_id = $this->getEstablishmentId($request->establishment);

        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;

            $records = Quotation::whereBetween('date_of_issue', [$d, $a])->latest()->get();

            /*if (is_null($td)) {
                $records = Purchase::with([ 'state_type', 'supplier'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
            }
            else {
                $records = Purchase::with([ 'state_type', 'supplier'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
            }*/
        } else {
            $records = Quotation::latest()->get();
            /* if (is_null($td)) {
                 $records = Purchase::with([ 'state_type', 'supplier'])
                     ->latest()
                     ->get();
             }
             else {
                 $records = Purchase::with([ 'state_type', 'supplier'])
                     ->where('document_type_id', $td)
                     ->latest()
                     ->get();
             }*/
        }

        /*if(!is_null($establishment_id)){
             $records = $records->where('establishment_id', $establishment_id);
         }*/

        return (new QuotationExport)
            ->records($records)
            ->company($company)
            // ->establishment($establishment)
            ->download('ReporteCotiz' . Carbon::now() . '.xlsx');
    }

    public function filter()
    {

        $document_types = [];

        $establishments = Establishment::all()->transform(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });

        $sellers = $this->getSellers();

        $state_types = $this->getStateTypesById(['01', '05', '09']);
        $users = $this->getUsers();

        return compact(
            'users',
            'document_types',
            'establishments',
            'sellers',
            'state_types'
        );
    }


//    public function index()
//    {
//
//        return view('report::quotations.index');
//    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request->all(), Quotation::class);

        return new QuotationCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function pdf(Request $request)
    {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), Quotation::class)->get();
        $filters = $request->all();

        $pdf = PDF::loadView('report::quotations.report_pdf', compact("records", "company", "establishment", "filters"))->setPaper('a4', 'landscape');

        $filename = 'Reporte_Cotizaciones_' . date('YmdHis');

        return $pdf->download($filename . '.pdf');
    }


    public function excel(Request $request)
    {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $records = $this->getRecords($request->all(), Quotation::class)->get();
        $filters = $request->all();

        return (new QuotationExport)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->filters($filters)
            ->download('Reporte_Cotizaciones_' . Carbon::now() . '.xlsx');

    }
}
