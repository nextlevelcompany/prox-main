<?php

namespace Modules\Catalog\Http\Controllers;

use Modules\Catalog\Http\Requests\UnitTypeRequest;
use Modules\Catalog\Http\Resources\UnitTypeCollection;
use Modules\Catalog\Http\Resources\UnitTypeResource;
use Modules\Catalog\Models\UnitType;
use App\Http\Controllers\Controller;
use Exception;

class UnitTypeController extends Controller
{
    public function records()
    {
        $records = UnitType::all();

        return new UnitTypeCollection($records);
    }

    public function record($id)
    {
        $record = new UnitTypeResource(UnitType::findOrFail($id));

        return $record;
    }

    public function store(UnitTypeRequest $request)
    {
        $id = $request->input('id');
        $unit_type = UnitType::firstOrNew(['id' => $id]);
        $unit_type->fill($request->all());
        $unit_type->save();

        return [
            'success' => true,
            'message' => ($id)?'Unidad editada con éxito':'Unidad registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        try {

            $record = UnitType::findOrFail($id);
            $record->delete();

            return [
                'success' => true,
                'message' => 'Unidad eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => 'La unidad esta siendo usada por otros registros, no puede eliminar'] : ['success' => false,'message' => 'Error inesperado, no se pudo eliminar la unidad'];

        }


    }
}
