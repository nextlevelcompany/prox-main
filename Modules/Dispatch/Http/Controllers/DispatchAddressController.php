<?php

namespace Modules\Dispatch\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Person\Models\Person;
use Illuminate\Http\Request;
use Modules\ApiPeruDev\Data\ServiceData;
use Modules\Dispatch\Http\Requests\DispatchAddressRequest;
use Modules\Dispatch\Models\DispatchAddress;
use Modules\Dispatch\Http\Resources\{
    DispatchAddressCollection,
    DispatchAddressResource
};
use Exception;


class DispatchAddressController extends Controller
{
    
    public function index()
    {
        return view('tenant.dispatches.dispatch-addresses.index');
    }


    public function columns()
    {
        return [
            'address' => 'Dirección',
        ];
    }
    
    
    /**
     *
     * @param  int $id
     * @return DispatchAddressResource
     */
    public function record($id)
    {
        return new DispatchAddressResource(DispatchAddress::findOrFail($id));
    }

    
    /**
     * 
     * Listado
     *
     * @param  Request $request
     * @return DispatchAddressCollection
     */
    public function records(Request $request)
    {
        $records = DispatchAddress::whereFilterRecords($request);

        return new DispatchAddressCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function tables()
    {
        $locations = func_get_table_locations();

        return [
            'locations' => $locations
        ];
    }


    public function store(DispatchAddressRequest $request)
    {
        $id = $request->input('id');
        $record = DispatchAddress::query()->firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'id' => $record->id
        ];
    }

    
    /**
     *
     * @param  int $id
     * @return array
     */
    public function destroy($id)
    {
        try 
        {
            $record = DispatchAddress::findOrFail($id);
            $record->delete();
    
            return [
                'success' => true,
                'message' => 'Dirección eliminada con éxito'
            ];
        } 
        catch (Exception $e) 
        {
            return ($e->getCode() == '23000') ? ['success' => false, 'message' => 'La dirección está siendo usada por otros registros, no puede eliminar'] : ['success' => false, 'message' => 'Error inesperado, no se pudo eliminar la dirección'];
        }
    }


    public function getOptions($person_id)
    {
        return DispatchAddress::query()
            ->where('person_id', $person_id)
            ->get()
            ->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'location_id' => $row->location_id,
                    'address' => $row->address
                ];
            });
    }


    public function searchAddress($person_id)
    {
        $person = Person::query()->find($person_id);
        if ($person->identity_document_type_id === '1') {
            $type = 'dni';
        } elseif ($person->identity_document_type_id === '6') {
            $type = 'ruc';
        } else {
            return [
                'success' => false,
                'message' => 'No se encontró dirección'
            ];
        }
        return (new ServiceData())->service($type, $person->number);
    }
}
