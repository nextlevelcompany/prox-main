<?php

namespace Modules\Establishment\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Modules\Document\Models\Document;
use Modules\Catalog\Models\DocumentType;
use Modules\Establishment\Http\Requests\SeriesConfigurationsRequest;
use Modules\Establishment\Models\Series;
use Modules\Dispatch\Models\Dispatch;
use Modules\Establishment\Models\SeriesConfiguration;

class SeriesConfigurationController extends Controller
{
    public function index()
    {
        return view('tenant.documents.series_configurations.index');
    }

    public function records()
    {
        $records = $this->getRecords();
        return $records;
    }

    public function getRecords()
    {
        $records = SeriesConfiguration::get()->transform(function ($row, $key) {

            if ($row->document_type_id == '09') {
                $quantity_documents = Dispatch::where('number', $row->number)->count();
            } else {
                $quantity_documents = $this->getQuantityDocuments($row->document_type_id, $row->series);
            }

            return [
                'id' => $row->id,
                'series_id' => $row->series_id,
                'document_type_description' => $row->document_type->description,
                'series' => $row->series,
                'number' => $row->number,
                'initialized_description' => ($quantity_documents > 0) ? 'SI' : 'NO',
                'btn_delete' => ($quantity_documents > 0) ? false : true
                // 'initialized_description' => ($row->relationSeries->documents->count() > 0) ? 'SI':'NO',
                // 'btn_delete' => ($row->relationSeries->documents->count() > 0) ? false:true
            ];
        });

        return $records;

    }

    public function tables()
    {
        $establishmentId = auth()->user()->establishment_id;
        $document_types = DocumentType::query()
            ->whereIn('id', ['01', '03', '07', '08', '09', 'U5', 'U6', 'U7'])
            ->get();

        $series = Series::query()
            ->whereIn('document_type_id', ['01', '03', '07', '08', '09', 'U5', 'U6', 'U7'])
            ->where('establishment_id', $establishmentId)
            ->doesntHave('series_configurations')
            ->get();

        return compact('series', 'document_types');
    }

    private function getQuantityDocuments($document_type_id, $series)
    {
        return Document::query()
            ->where([['document_type_id', $document_type_id], ['series', $series]])
            ->count();
    }

    public function store(SeriesConfigurationsRequest $request)
    {
        if ($request->document_type_id == '09') {
            $number = Dispatch::max('number');
            if ($request->number <= $number) {
                return [
                    'success' => false,
                    'message' => 'Ya inicializó el número correlativo de la serie'
                ];
            }
        }

        $quantity_document = $this->getQuantityDocuments($request->document_type_id, $request->series);

        if ($quantity_document > 0) {
            return [
                'success' => false,
                'message' => 'Ya inicializó el número correlativo de la serie'
            ];
        }

        $id = $request->input('id');
        $record = SeriesConfiguration::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Configuración editada con éxito' : 'Configuración registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        try {

            $record = SeriesConfiguration::findOrFail($id);
            $record->delete();

            return [
                'success' => true,
                'message' => 'Configuración de serie eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false, 'message' => 'La Configuración de serie esta siendo usada por otros registros, no puede eliminar'] : ['success' => false, 'message' => 'Error inesperado, no se pudo eliminar la Configuración de serie'];
        }
    }
}
