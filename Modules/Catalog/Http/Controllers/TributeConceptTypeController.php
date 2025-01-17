<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Catalog\Http\Requests\TributeConceptTypeRequest;
use Modules\Catalog\Http\Resources\TributeConceptTypeCollection;
use Modules\Catalog\Http\Resources\TributeConceptTypeResource;
use Modules\Catalog\Models\AttributeType;
use Exception;

class TributeConceptTypeController extends Controller
{
    public function records()
    {
        $records = new TributeConceptTypeCollection(AttributeType::all());

        return $records;
    }

    public function record($id)
    {
        $record = new TributeConceptTypeResource(AttributeType::findOrFail($id));

        return $record;
    }

    public function store(TributeConceptTypeRequest $request)
    {
        $id = $request->input('id');
        $tribute_concept_type = AttributeType::firstOrNew(['id' => $id]);
        $tribute_concept_type->fill($request->all());
        $tribute_concept_type->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Atributo editado con éxito':'Atributo registrado con éxito'
        ];
    }



    public function destroy($id)
    {

        try {

            $record = AttributeType::findOrFail($id);
            $record->delete();

            return [
                'success' => true,
                'message' => 'Atributo eliminado con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => 'El atributo esta siendo usado por otros registros, no puede eliminar'] : ['success' => false,'message' => 'Error inesperado, no se pudo eliminar el atributo'];

        }
    }
}
