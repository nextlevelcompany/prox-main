<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Subscription\Models\SubscriptionGrade;
use Modules\Subscription\Http\Resources\GradeCollection;
use Modules\Subscription\Http\Resources\GradeResource;
use Modules\Subscription\Http\Requests\GradeRequest;

class GradeController extends Controller
{
    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = SubscriptionGrade::where($request->column, 'like', "%{$request->value}%")->latest('id');

        return new GradeCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        return new GradeResource(SubscriptionGrade::findOrFail($id));
    }

    public function store(GradeRequest $request)
    {
        $id = $request->input('id');

        $record = SubscriptionGrade::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Grado editado con éxito' : 'Grado registrado con éxito',
        ];
    }

    public function destroy($id)
    {
        $record = SubscriptionGrade::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Grado eliminado con éxito'
        ];
    }

}
