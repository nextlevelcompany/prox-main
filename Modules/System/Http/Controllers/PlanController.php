<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\System\Http\Requests\PlanRequest;
use Modules\System\Http\Resources\PlanCollection;
use Modules\System\Http\Resources\PlanResource;
use Modules\System\Models\Plan;
use Modules\System\Models\PlanDocument;

class PlanController extends Controller
{
    public function index()
    {
        return view('system.plans.index');
    }

    public function records()
    {
        $records = Plan::all();

        return new PlanCollection($records);
    }

    public function record($id)
    {
        $record = new PlanResource(Plan::findOrFail($id));

        return $record;
    }

    public function tables()
    {
        $plan_documents = PlanDocument::all();

        return compact('plan_documents');
    }


    public function store(PlanRequest $request)
    {
        $id = $request->input('id');
        $plan = Plan::query()->firstOrNew(['id' => $id]);
        $plan->fill($request->all());
        $plan->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Plan editado con éxito' : 'Plan registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return [
            'success' => true,
            'message' => 'Plan eliminado con éxito'
        ];
    }

}
