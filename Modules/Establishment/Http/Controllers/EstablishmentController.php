<?php

namespace Modules\Establishment\Http\Controllers;

use Modules\Catalog\Models\Country;
use Modules\Catalog\Models\Department;
use Modules\Catalog\Models\District;
use Modules\Catalog\Models\Province;
use Modules\Establishment\Http\Requests\EstablishmentRequest;
use Modules\Establishment\Http\Resources\EstablishmentCollection;
use Modules\Establishment\Http\Resources\EstablishmentResource;
use Modules\Establishment\Models\Establishment;
use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Warehouse;
use Modules\Person\Models\Person;
use Modules\Finance\Helpers\UploadFileHelper;
use Exception;

class EstablishmentController extends Controller
{
    public function index()
    {
        return view('tenant.establishments.index');
    }

    public function create()
    {
        return view('tenant.establishments.form');
    }

    public function tables()
    {
        $countries = func_get_table_countries();
        $departments = Department::whereActive()->orderByDescription()->get();
        $provinces = Province::whereActive()->orderByDescription()->get();
        $districts = District::whereActive()->orderByDescription()->get();

        $customers = Person::whereType('customers')->orderBy('name')->take(1)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
            ];
        });

        return compact('countries', 'departments', 'provinces', 'districts', 'customers');
    }

    public function record($id)
    {
        $record = new EstablishmentResource(Establishment::findOrFail($id));

        return $record;
    }

    public function store(EstablishmentRequest $request)
    {
        try
        {
            $id = $request->input('id');
            $has_igv_31556 = ($request->input('has_igv_31556') === 'true');
            $establishment = Establishment::firstOrNew(['id' => $id]);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $request->validate(['file' => 'mimes:jpeg,png,jpg|max:1024']);
                $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;

                UploadFileHelper::checkIfValidFile($filename, $file->getPathName(), true);

                $file->storeAs('public/uploads/logos', $filename);
                $path = 'storage/uploads/logos/' . $filename;
                $request->merge(['logo' => $path]);
            }
            $establishment->fill($request->all());
            $establishment->has_igv_31556 = $has_igv_31556;
            $establishment->email = $request->email;
            $establishment->save();

            if(!$id) {
                $warehouse = new Warehouse();
                $warehouse->establishment_id = $establishment->id;
                $warehouse->description = 'Almacén - '.$establishment->description;
                $warehouse->save();
            }

            return [
                'success' => true,
                'message' => ($id)?'Establecimiento actualizado':'Establecimiento registrado'
            ];
        }
        catch(Exception $e)
        {
            $this->generalWriteErrorLog($e);

            return $this->generalResponse(false, 'Error desconocido: '.$e->getMessage());
        }
    }


    public function records()
    {
        $records = Establishment::all();

        return new EstablishmentCollection($records);
    }

    public function destroy($id)
    {
        $establishment = Establishment::findOrFail($id);
        $establishment->delete();

        return [
            'success' => true,
            'message' => 'Establecimiento eliminado con éxito'
        ];
    }
}
