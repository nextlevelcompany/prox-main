<?php

namespace Modules\FullSubscription\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\FullSubscription\Http\Requests\PlanFullSubscriptionRequest;
use Modules\FullSubscription\Http\Resources\SubscriptionPlansCollection;
use Modules\FullSubscription\Http\Resources\SubscriptionPlansResource;
use Modules\FullSubscription\Models\CatPeriod;
use Modules\FullSubscription\Models\SubscriptionPlan;

class PlansFullSubscriptionController extends FullSubscriptionController
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
        /** @var Builder $records */
        $records->orderBy('name');
        // ->where('type', $type)
        return new SubscriptionPlansCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function Tables()
    {

        $periods = CatPeriod::where('active', 1)->get();
        $establishment_id = auth()->user()->establishment_id;

        return compact(
            'periods',
            'establishment_id'
        );

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Factory|Application|Response|View
     */
    public function edit($id)
    {
        return view('full_subscription::edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|Response|View
     */
    public function index()
    {
        return view('tenant.full_subscription.plans.index');
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return Factory|Application|Response|View
     */
    public function show($id)
    {
        return view('full_subscription::show');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param PlanFullSubscriptionRequest $request
     *
     * @return SubscriptionPlansResource
     */
    public function store(PlanFullSubscriptionRequest $request)
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

}
