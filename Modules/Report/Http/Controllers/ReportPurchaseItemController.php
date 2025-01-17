<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Resources\Tenant\PurchaseItemCollection;
use Modules\Catalog\Models\DocumentType;
use App\Http\Controllers\Controller;
use Modules\Company\Models\Configuration;
use Modules\Purchase\Models\PurchaseItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Report\Exports\PurchaseExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportTrait;
use Modules\Establishment\Models\Establishment;
use Modules\Purchase\Models\Purchase;
use Modules\Company\Models\Company;
use Carbon\Carbon;
use App\Http\Resources\Tenant\PurchaseCollection;

/**
 * Class ReportPurchaseController
 *
 * @package Modules\Report\Http\Controllers
 */
class ReportPurchaseItemController extends Controller
{
    use ReportTrait;

    public function general_items(Request $request)
    {
        $typeresource = 'reports/purchases/general_items';
        $typereport = 'purchase';
        $configuration = Configuration::getPublicConfig();
        $apply_conversion_to_pen = $this->applyConversiontoPen($request);

        return view('report::general_items.index',compact('typeresource','typereport','configuration', 'apply_conversion_to_pen'));
    }

    /**
     * @return array
     */
    public function filter() {

        $document_types = DocumentType::whereIn('id', ['01', '03','GU75', 'NE76'])->get();

        $persons = $this->getPersons('suppliers');
        $sellers = $this->getSellers();

        $establishments = Establishment::all()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });

        return compact('document_types','establishments', 'persons', 'sellers');
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index() {

        $typereport = 'purchase';
        $configuration = Configuration::getPublicConfig();
        return view('report::purchases_items.index',compact('typereport','configuration'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\Tenant\PurchaseItemCollection
     */
    public function records(Request $request)
    {
        $records = $this->getRecords($request->all(), PurchaseItem::class);

        return new PurchaseItemCollection($records->paginate(config('tenant.items_per_page')));
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), Purchase::class)->get();
        $filters = $request->all();

        $pdf = PDF::loadView('report::purchases.report_pdf', compact("records", "company", "establishment", "filters"))->setPaper('a4', 'landscape');

        $filename = 'Reporte_Compras_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excel(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), Purchase::class)->get();
        $filters = $request->all();

        return (new PurchaseExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->filters($filters)
                ->download('Reporte_Compras_'.Carbon::now().'.xlsx');

    }
}
