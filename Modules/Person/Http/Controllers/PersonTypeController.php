<?php

namespace Modules\Person\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Person\Http\Requests\PersonTypeRequest;
use Modules\Person\Http\Resources\PersonTypeCollection;
use Modules\Person\Models\PersonType;
use Exception;
use Illuminate\Http\Request;

class PersonTypeController extends Controller
{
    public function index()
    {
        return view('tenant.person_types.index');
    }

    public function columns()
    {
        return [
            'description' => 'Descripción',
        ];
    }

    public function records(Request $request)
    {

        $records = PersonType::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new PersonTypeCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.customers.form');
    }


    public function record($id)
    {
        $record = PersonType::findOrFail($id);

        return $record;
    }

    public function store(PersonTypeRequest $request)
    {
        $id = $request->input('id');
        $person_type = PersonType::firstOrNew(['id' => $id]);
        $person_type->fill($request->all());
        $person_type->save();


        return [
            'success' => true,
            'message' => ($id)?'Tipo de cliente editado con éxito':'Tipo de cliente registrado con éxito',
        ];
    }

    public function destroy($id)
    {
        try {

            $person_type = PersonType::findOrFail($id);
            $person_type_type = 'Tipo de cliente';
            $person_type->delete();

            return [
                'success' => true,
                'message' => $person_type_type.' eliminado con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "El {$person_type_type} esta siendo usado por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar el {$person_type_type}"];

        }

    }

}
