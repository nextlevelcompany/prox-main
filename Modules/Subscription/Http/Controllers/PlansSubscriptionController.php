<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Subscription\Http\Requests\PlanSubscriptionRequest;
use Modules\Subscription\Http\Resources\SubscriptionPlansCollection;
use Modules\Subscription\Http\Resources\SubscriptionPlansResource;
use Modules\Subscription\Models\CatPeriod;
use Modules\Subscription\Models\SubscriptionPlan;

class PlansSubscriptionController extends SubscriptionController
{
    public function Columns()
    {
        return [
            'cat_period_id' => 'Periodos',
            'name' => 'Descripción',
            'description' => 'Nombre',
        ];
    }

    public function Record(Request $request)
    {
        return new SubscriptionPlansResource(SubscriptionPlan::findOrFail($request->person));
    }

    public function Records(Request $request)
    {
        $records = SubscriptionPlan::query();
        if ($request->has('column') && !empty($request->column)) {
            $records->where($request->column, 'like', "%{$request->value}%");
        }
        /** @var \Illuminate\Database\Query\Builder $records */
        $records->orderBy('name');
        // ->where('type', $type)
        return new SubscriptionPlansCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function Tables()
    {

        $periods = CatPeriod::where('active', 1)->get();

        return compact(
            'periods'
        );

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('subscription::edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('tenant.subscription.plans.index');
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
        return view('subscription::show');
    }

    public function store(PlanSubscriptionRequest $request)
    {
        $id = null;
        $requestItems = $request->items;
        if ($request->has('id')) $id = (int)$request->id;
        $period = CatPeriod::where('period', 'Y')->first();
        if ($request->has('periods')) {
            if (CatPeriod::where('period', $request->periods)->first() != null) {
                $period = CatPeriod::where('period', $request->periods)->first();
            }
        }
        $plan = SubscriptionPlan::firstOrNew(['id' => $id], $request->all());

        $plan->fill($request->all());
        $plan->setName($request->name)
            ->setDescription($request->description)
            ->setCatPeriod($period)
            ->push();
        foreach ($plan->items as $i) {
            $i->delete();
        }
        // Elimina todos los items anteriores
        // Inserta todos los nuevos items
        foreach ($requestItems as $item) {
            $plan->items()->create($item);

        }


        return new SubscriptionPlansResource($plan);

    }

    public function destroy($id)
    {

        $record = SubscriptionPlan::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Plan eliminado con éxito'
        ];

    }

}
