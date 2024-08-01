<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Modules\Catalog\Http\Requests\PaymentMethodTypeRequest;
use Modules\Catalog\Http\Resources\PaymentMethodTypeCollection;
use Modules\Catalog\Http\Resources\PaymentMethodTypeResource;
use Modules\Catalog\Models\PaymentMethodType;

class PaymentMethodTypeController extends Controller
{
    public function records()
    {
        $records = PaymentMethodType::all();

        return new PaymentMethodTypeCollection($records);
    }

    public function record($id)
    {
        $record = new PaymentMethodTypeResource(PaymentMethodType::findOrFail($id));
        return $record;
    }

    public function store(PaymentMethodTypeRequest $request)
    {
        $id = $request->input('id');
        $unit_type = PaymentMethodType::firstOrNew(['id' => $id]);
        $unit_type->fill($request->all());
        $unit_type->save();

        return [
            'success' => true,
            'message' => ($id)?'Método de pago editada con éxito':'Método de pago registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        try {

            $record = PaymentMethodType::findOrFail($id);
            $record->delete();

            return [
                'success' => true,
                'message' => 'Método de pago eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => 'El método de pago esta siendo usada por otros registros, no puede eliminar'] : ['success' => false,'message' => 'Error inesperado, no se pudo eliminar la unidad'];

        }


    }
}
