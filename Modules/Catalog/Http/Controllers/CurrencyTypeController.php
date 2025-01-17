<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Catalog\Http\Requests\CurrencyTypeRequest;
use Modules\Catalog\Http\Resources\CurrencyTypeCollection;
use Modules\Catalog\Http\Resources\CurrencyTypeResource;
use Modules\Catalog\Models\CurrencyType;
use Exception;

class CurrencyTypeController extends Controller
{
    public function records()
    {
        $records = CurrencyType::all();

        return new CurrencyTypeCollection($records);
    }

    public function record($id)
    {
        $record = new CurrencyTypeResource(CurrencyType::findOrFail($id));

        return $record;
    }

    public function store(CurrencyTypeRequest $request)
    {
        $id = $request->input('id');
        $currency_type = CurrencyType::firstOrNew(['id' => $id]);
        $currency_type->fill($request->all());
        $currency_type->save();

        return [
            'success' => true,
            'message' => ($id)?'Moneda editada con éxito':'Moneda registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        try {

            $currency_type = CurrencyType::findOrFail($id);
            $currency_type->delete();

            return [
                'success' => true,
                'message' => 'Moneda eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => 'La moneda esta siendo usada por otros registros, no puede eliminar'] : ['success' => false,'message' => 'Error inesperado, no se pudo eliminar la moneda'];

        }
    }
}
