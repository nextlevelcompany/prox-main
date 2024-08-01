<?php

namespace Modules\Voided\Http\Controllers;

use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Modules\Document\Models\Document;
use Modules\Company\Models\Configuration;
use Modules\Voided\Http\Requests\VoidedRequest;
use Modules\Voided\Http\Resources\VoidedCollection;
use Modules\Voided\Models\Voided;

class VoidedController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('input.request:voided,web', ['only' => ['store']]);
    }

    public function index()
    {
        return view('tenant.voided.index');
    }

    public function columns()
    {
        return [
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
        $voided = DB::connection('tenant')
                    ->table('voided')
                    ->where($request->column, 'like', "%{$request->value}%")
                    ->select(DB::raw("id, external_id, date_of_reference, date_of_issue, ticket, identifier, state_type_id, 'voided' AS 'type'"));

        $summaries = DB::connection('tenant')
                        ->table('summaries')
                        ->select(DB::raw("id, external_id, date_of_reference, date_of_issue, ticket, identifier, state_type_id, 'summaries' AS 'type'"))
                        ->where($request->column, 'like', "%{$request->value}%")
                        ->where('summary_status_type_id', '3');

        return new VoidedCollection($voided->union($summaries)->orderBy('date_of_issue', 'DESC')->paginate(config('tenant.items_per_page')));
    }

    public function store(VoidedRequest $request)
    {
        $validate = $this->validateVoided($request);
        if(!$validate['success']) return $validate;

        $fact = DB::connection('tenant')->transaction(function () use($request) {
            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->senderXmlSignedSummary();

            return $facturalo;
        });

        $document = $fact->getDocument();
        //$response = $fact->getResponse();

        return [
            'success' => true,
            'message' => "La anulación {$document->identifier} fue creado correctamente",
        ];
    }


    /**
     * Validaciones previas
     *
     * @param VoidedRequest $request
     * @return array
     */
    public function validateVoided($request)
    {

        $configuration = Configuration::select('restrict_voided_send', 'shipping_time_days_voided')->firstOrFail();
        $voided_date_of_issue = Carbon::parse($request->date_of_issue);

        if($configuration->restrict_voided_send)
        {
            foreach ($request->documents as $row)
            {
                $document = Document::whereFilterWithOutRelations()->select('date_of_issue')->findOrFail($row['document_id']);

                $difference_days = $configuration->shipping_time_days_voided - $document->getDiffInDaysDateOfIssue($voided_date_of_issue);

                if($difference_days < 0)
                {
                    return [
                        'success' => false,
                        'message' => "El documento excede los {$configuration->shipping_time_days_voided} días válidos para ser anulado."
                    ];
                }
            }
        }

        return [
            'success' => true,
            'message' => null
        ];

    }


    public function status($voided_id)
    {
        $document = Voided::find($voided_id);

        $fact = DB::connection('tenant')->transaction(function () use($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->setType('voided');
            $facturalo->statusSummary($document->ticket);
            return $facturalo;
        });

        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }

    public function status_masive()
    {

        $records = Voided::where('state_type_id', '03')->get();

        $fact = DB::connection('tenant')->transaction(function () use($records) {

            foreach ($records as $document) {

                $facturalo = new Facturalo();
                $facturalo->setDocument($document);
                $facturalo->setType('voided');
                $facturalo->statusSummary($document->ticket);
            }
        });

        return [
            'success' => true,
            'message' => "Consulta masiva ejecutada.",
        ];
    }

    public function destroy($voided_id)
    {
        $document = Voided::find($voided_id);
        foreach ($document->documents as $doc)
        {
            $doc->document->update([
                'state_type_id' => '05'
            ]);
        }
        $document->delete();

        return [
            'success' => true,
            'message' => 'Anulación eliminada con éxito'
        ];
    }
}
