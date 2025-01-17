<?php

namespace Modules\Summary\Http\Controllers\Api;

use App\CoreFacturalo\Facturalo;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Summary\Models\Summary;

class SummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('input.request:summary,api', ['only' => ['store']]);
    }

    public function store(Request $request)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($request) {
            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->senderXmlSignedSummary();

            return $facturalo;
        });
        $document = $fact->getDocument();

        return [
            'success' => true,
            'data' => [
                'external_id' => $document->external_id,
                'ticket' => $document->ticket,
            ]
        ];
    }

    public function status(Request $request)
    {
        if ($request->has('external_id')) {
            $external_id = $request->input('external_id');
            $summary = Summary::query()
                ->where('external_id', $external_id)
                ->first();
            if (!$summary) {
                throw new Exception("El código externo {$external_id} es inválido, no se encontró resumen relacionado");
            }
        } elseif ($request->has('ticket')) {
            $ticket = $request->input('ticket');
            $summary = Summary::query()
                ->where('ticket', $ticket)
                ->first();
            if (!$summary) {
                throw new Exception("El ticket {$ticket} es inválido, no se encontró resumen relacionado");
            }
        } else {
            throw new Exception('Es requerido el código externo o ticket');
        }

        $facturalo = new Facturalo();
        $facturalo->setDocument($summary);
        $facturalo->setType('summary');
        $facturalo->statusSummary($summary->ticket);

        $response = $facturalo->getResponse();

        return [
            'success' => true,
            'data' => [
                'filename' => $summary->filename,
                'external_id' => $summary->external_id
            ],
            'links' => [
                'xml' => $summary->download_external_xml,
                'cdr' => $summary->download_external_cdr,
            ],
            'response' => $response
        ];
    }
}
