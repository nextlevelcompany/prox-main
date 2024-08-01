<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Subscription\Models\SubscriptionSection;
use Modules\Subscription\Http\Resources\SectionCollection;
use Modules\Subscription\Http\Resources\SectionResource;
use Modules\Subscription\Http\Requests\SectionRequest;

class SectionController extends Controller
{
    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = SubscriptionSection::where($request->column, 'like', "%{$request->value}%")->latest('id');

        return new SectionCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        return new SectionResource(SubscriptionSection::findOrFail($id));
    }

    public function store(SectionRequest $request)
    {

        $id = $request->input('id');

        $record = SubscriptionSection::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Sección editada con éxito' : 'Sección registrada con éxito',
        ];
    }

    public function destroy($id)
    {
        $record = SubscriptionSection::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Sección eliminada con éxito'
        ];
    }

}
