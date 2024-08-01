<?php

namespace Modules\Retention\Http\Controllers;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use Modules\Catalog\Models\RetentionType;
use Modules\Establishment\Models\Establishment;
use Modules\Establishment\Models\Series;
use Modules\Retention\Http\Requests\RetentionRequest;
use Modules\Retention\Http\Resources\RetentionCollection;
use Modules\Retention\Http\Resources\RetentionResource;
use Modules\Retention\Models\Retention;
use Exception;
use Illuminate\Http\Request;
use Modules\Person\Models\Person;
use Modules\Catalog\Models\CurrencyType;
use Modules\Catalog\Models\DocumentType;
use Illuminate\Support\Facades\DB;
use App\CoreFacturalo\Facturalo;

class RetentionController extends Controller
{
    use StorageDocument;

    public function __construct() {
        $this->middleware('input.request:retention,web', ['only' => ['store']]);
    }

    public function index()
    {
        return view('tenant.retentions.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Retention::where($request->column, 'like', "%{$request->value}%")
                            // ->orderBy('series')
                            // ->orderBy('number', 'desc');
                            ->latest();

        return new RetentionCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.retentions.form');
    }

    public function tables()
    {
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();// Establishment::all();
        $retention_types = RetentionType::get();
        $suppliers = $this->table('suppliers');
        $series = Series::all();

        return compact('establishments', 'retention_types', 'suppliers', 'series');
    }

    public function document_tables()
    {
        $retention_types = RetentionType::get();
        $currency_types = func_get_table_currency_types();
        $document_types = DocumentType::whereIn('id', ['01', '03'])->get();

        return compact('document_types', 'currency_types', 'retention_types');
    }

    public function table($table)
    {
        if ($table === 'suppliers') {

            $suppliers = Person::whereType('suppliers')->where('identity_document_type_id', '6')->orderBy('name')->get()->transform(function($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number.' - '.$row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code
                ];
            });
            return $suppliers;
        }

        return [];
    }

    public function record($id)
    {
        $record = new RetentionResource(Retention::findOrFail($id));

        return $record;
    }

    public function store(RetentionRequest $request)
    {

        $fact = DB::connection('tenant')->transaction(function () use($request) {
            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->createPdf();
            $facturalo->senderXmlSignedBill();

            return $facturalo;
        });

        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => "Se generó la retención {$document->series}-{$document->number}",
            'data' => [
                'id' => $document->id,
                'response' =>$response

            ],
        ];
    }

    public function downloadExternal($type, $external_id)
    {
        $retention = Retention::where('external_id', $external_id)->first();
        if(!$retention) {
            throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        }

        switch ($type) {
            case 'pdf':
                $folder = 'pdf';
                break;
            case 'xml':
                $folder = 'signed';
                break;
            case 'cdr':
                $folder = 'cdr';
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($retention->filename, $folder);
    }
}
