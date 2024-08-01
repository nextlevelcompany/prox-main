<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Catalog\Http\Requests\TransferReasonTypeRequest;
use Modules\Catalog\Http\Resources\TransferReasonTypeCollection;
use Modules\Catalog\Http\Resources\TransferReasonTypeResource;
use Modules\Catalog\Models\TransferReasonType;

class TransferReasonTypeController extends Controller
{
    public function records()
    {
        $records = TransferReasonType::all();

        return new TransferReasonTypeCollection($records);
    }

    public function record($id)
    {
        $record = new TransferReasonTypeResource(TransferReasonType::findOrFail($id));

        return $record;
    }

    public function store(TransferReasonTypeRequest $request)
    {
        $id = $request->input('id');
        $record = TransferReasonType::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Motivo de traslado editado con éxito':'Motivo de traslado registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $record = TransferReasonType::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Motivo de traslado eliminado con éxito'
        ];
    }
}
