<?php

namespace Modules\Report\Http\Controllers;

use Modules\Catalog\Models\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Item\Models\WebPlatform;
use Modules\Report\Exports\SaleNoteExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportTrait;
use Modules\Establishment\Models\Establishment;
use Modules\SaleNote\Http\Resources\SaleNoteCollection;
use Modules\SaleNote\Models\SaleNote;
use Modules\Company\Models\Company;
use Carbon\Carbon;

class ReportSaleNoteController extends Controller
{
    use ReportTrait;


    public function filter() {

        $document_types = [];

        $establishments = Establishment::all()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });

        $sellers = $this->getSellers();
        $web_platforms = WebPlatform::get();
        $users = $this->getUsers();


        return compact(
            'users',
            'document_types',
            'establishments',
            'sellers',
            'web_platforms'
        );
    }


    public function index() {

        return view('report::sale_notes.index');
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request->all(), SaleNote::class);

        return new SaleNoteCollection($records->paginate(config('tenant.items_per_page')));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), SaleNote::class)->get();
        $filters = $request->all();
        $pdf = PDF::loadView('report::sale_notes.report_pdf', compact("records", "company", "establishment", "filters"))->setPaper('a4', 'landscape');
        $filename = 'Reporte_Nota_Ventas_'.date('YmdHis');
        return $pdf->download($filename.'.pdf');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excel(Request $request)
    {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $records = $this->getRecords($request->all(), SaleNote::class)->get();
        $filters = $request->all();
        $SaleNoteExport = new SaleNoteExport();

        $SaleNoteExport
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->filters($filters);

        return $SaleNoteExport->download('Reporte_Nota_Ventas_' . Carbon::now() . '.xlsx');

    }
}
