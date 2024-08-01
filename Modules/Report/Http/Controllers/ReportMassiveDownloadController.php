<?php

namespace Modules\Report\Http\Controllers;

use Modules\Catalog\Models\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Report\Exports\DocumentHotelExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportTrait;
use Modules\Establishment\Models\Establishment;
use Modules\Company\Models\Configuration;
use Modules\Company\Models\Company;
use App\Models\Tenant\{
    Document,
    SaleNote,
    Dispatch
};
use Carbon\Carbon;
use Modules\Report\Traits\MassiveDownloadTrait;

class ReportMassiveDownloadController extends Controller
{

    use ReportTrait, MassiveDownloadTrait;

    public function index()
    {
        return view('report::massive-downloads.index');
    }


    public function filter() {

        $document_types = DocumentType::whereIn('id', ['01', '03','80', '09'])->get();
        $sellers = $this->getSellers();
        $series = $this->getSeries($document_types);

        $persons = $this->getPersons('customers');

        return compact('document_types','persons','sellers','series');
    }


    public function records(Request $request)
    {

        $params = json_decode($request->form);
        $document_types = $params->document_types;

        if(count($document_types) == 0){
            $document_types = ['all'];
        }

        return [
            'total' => $this->getTotals($document_types, $params)
        ];

    }


    public function pdf(Request $request) {

        //dd($request->all());
        $array = json_decode($request->form,true);
        $params = json_decode($request->form);

        $document_types = $params->document_types;

        if(count($document_types) == 0){
            $document_types = ['all'];
        }
        $height = isset($array['height'])?$array['height']:'a4';

        $data = $this->getData($document_types, $params);
        $view =  $this->createPdf($data,$height,$array);

        return $this->toPrintByView('massive_downloads',$view);

    }


}
