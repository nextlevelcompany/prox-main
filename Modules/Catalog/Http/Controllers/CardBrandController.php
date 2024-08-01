<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Modules\Catalog\Http\Requests\CardBrandRequest;
use Modules\Catalog\Models\CardBrand;

class CardBrandController extends Controller
{
    public function records()
    {
        $records = CardBrand::all();

        return $records;
    }

    public function record($id)
    {
        $record = CardBrand::findOrFail($id);

        return $record;
    }

    public function store(CardBrandRequest $request)
    {
        $id = $request->input('id');
        $is_update = $request->input('is_update');
        $card_brand = CardBrand::firstOrNew(['id' => $id]);
        $card_brand->fill($request->all());
        $card_brand->save();

        return [
            'success' => true,
            'message' => ($is_update) ? 'Tarjeta editada con éxito':'Tarjeta registrada con éxito',
            'id' => $card_brand->id
        ];
    }



    public function destroy($id)
    {
        try {

            $card_brand = CardBrand::findOrFail($id);
            $card_brand->delete();

            return [
                'success' => true,
                'message' => 'Tarjeta eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => 'La Tarjeta esta siendo usada por otros registros, no puede eliminar'] : ['success' => false,'message' => 'Error inesperado, no se pudo eliminar la tarjeta'];

        }
    }
}
