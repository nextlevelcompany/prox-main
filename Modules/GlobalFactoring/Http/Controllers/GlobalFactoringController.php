<?php

namespace Modules\GlobalFactoring\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use GuzzleHttp\Client as GuzzleClient;
use Modules\GlobalFactoring\Models\ApiState;
use Modules\Document\Models\Document;
use Modules\Company\Models\Company;

class GlobalFactoringController extends Controller
{
    public function send(Request $request)
    {
        $response = $this->sendToApi('send', $request);

        return [
            'success' => $response['success'],
            'message' => $response['message'],
            'state' => $response['state']
        ];
    }

    public function query(Request $request)
    {
        $response = $this->sendToApi('query', $request);

        return [
            'success' => $response['success'],
            'message' => $response['message'],
            'state' => $response['state']
        ];
    }

    /*
     * Se envia al API mediante 2 metodos; envio y consulta, se guarda el estado resultando en el campo de la bd en caso de recibir un estado
     * existente en la base de datos, de no haber respuesta no se alteran los datos, se retorna siempre un estado para poder modificar los datos
     * en el modal del front en caso de que exista
     */
    public function sendToApi($method, $request)
    {
        try {
            $client = new GuzzleClient(['verify' => false]);

            switch ($method) {
                case 'send':
                    $api_url = config('configuration.url_globalfactoring').'insert';
                    $json = $this->generateJson($request);

                    $send = $client->post($api_url, [
                        'headers' => [
                            'Content-Type' => 'Application/json',
                        ],
                        'json' => $json
                    ]);
                    break;

                case 'query':
                    $api_url = config('configuration.url_globalfactoring').'ConsultaEstado';
                    $send = $client->get($api_url, [
                        'query' => [
                            'GW_Clavecliente' => config('configuration.key_globalfactoring'),
                            'Gfintrucpagador' => $request->customer_number,
                            'Gfintdocumento' => $request->number,
                        ]
                    ]);
                    break;
            }

            $code_status = $send->getStatusCode();

            if($code_status == 200) {
                $response = json_decode($send->getBody()->getContents(), true);
                $state = $this->getState($response['Estado']);
                if($state != null) {
                    $this->setState($request->id, $state);
                }

                return [
                    'success' => true,
                    'message' => 'Estado de comprobante: '.$response['Estado'],
                    'state' => $state
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se ha obtenido la respuesta deseada, intente de nuevo más tarde. Estado respuesta:'.$code_status,
                    'state' => null
                ];
            }
        } catch (\Throwable $error) {
            return [
                'success' => false,
                'message' => $error->getMessage(),
                'state' => null
            ];
        }
    }

    public function generateJson($request)
    {
        $company = Company::first();

        $json = [ "SDTGlobalWin" =>
            [
                [
                    "GW_ClaveCliente" => config('configuration.key_globalfactoring'),
                    "GFIntFec" => $request->date_of_issue,
                    "GFIntRUC" => $company->number,
                    "GFIntRazonSocial" => $company->name,
                    "GFIntRUCPagador" => $request->customer_number,
                    "GFIntRazonSocialPagador" => $request->customer_name,
                    "GFIntDocumento" => $request->number,
                    "GFIntImporte" => (string)bcdiv($request->total, 1, 2),
                    "GFIntLink" => $request->download_pdf,
                    "GFIntSistema" => "FACTURALOPERU",
                    "GFIntCadena" => "",
                    "GFXMLFile" => "",
                    "GFMoneda" => $request->currency_type_id,
                    "GFContacto" => auth()->user()->name,
                    "GFTelefono" => auth()->user()->telephone,
                    "GFEmail" => auth()->user()->email,
                ]
            ]
        ];

        return $json;
    }

    public function getState($value)
    {
        $state = ApiState::where('name', $value)->first();
        return $state;
    }

    public function setState($id, $state)
    {
        $document = Document::find($id);
        $document->collect_api_state_id = $state->id;
        $document->save();
    }
}