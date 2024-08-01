<?php

namespace Modules\FullSubscription\Http\Controllers;

use App\Helpers\SearchCustomerHelper;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\FullSubscription\Http\Requests\PaymentsFullSubscriptionRequest;
use Modules\FullSubscription\Http\Resources\UserRelSubscriptionPlansCollection;
use Modules\FullSubscription\Http\Resources\UserRelSubscriptionPlansResource;
use Modules\FullSubscription\Models\CatPeriod;
use Modules\FullSubscription\Models\SubscriptionPlan;
use Modules\FullSubscription\Models\UserRelSubscriptionPlan;

class PaymentsFullSubscriptionController extends FullSubscriptionController
{
    public function Columns()
    {
        return [
            'cat_period_id' => 'Periodos',
            // 'name' => 'Descripción',
            // 'description' => 'Nombre',
        ];

    }

    public function Record(Request $request)
    {
        return new UserRelSubscriptionPlansResource(UserRelSubscriptionPlan::findOrFail($request->person));
    }

    public function Records(Request $request)
    {
        $records = UserRelSubscriptionPlan::query();
        if ($request->has('column') && !empty($request->column)) {
            $records->where($request->column, 'like', "%{$request->value}%");
        }
        /** @var Builder $records */
        // $records->orderBy('name');
        // ->where('type', $type)
        return new UserRelSubscriptionPlansCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function Tables()
    {

        $customers = SearchCustomerHelper::getSubscriptionCustomers();
        $periods = CatPeriod::where('active', 1)->get();
        $startDate = Carbon::createFromFormat('Y-m-d', '2022-01-01')->format('Y-m-d');
        $plans = SubscriptionPlan::where('id', '!=', 0)
            ->get()
            ->transform(function ($row) {
                return $row->getCollectionData();
            });

        $establishment_id = auth()->user()->establishment_id;

        return compact(
            'periods',
            'customers',
            'startDate',
            'plans',
            'establishment_id'
        );

    }

    public function destroy($id)
    {
        $record = UserRelSubscriptionPlan::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Matrícula eliminada con éxito'
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
        return view('tenant.full_subscription.payments.index');
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

    public function searchCustomer(Request $request)
    {
        $customers = SearchCustomerHelper::getSubscriptionCustomers($request);

        return ['customers' => $customers];

    }

    public function store(PaymentsFullSubscriptionRequest $request)
    {
        $id = null;
        if ($request->has('id')) $id = (int)$request->id;
        $plan = UserRelSubscriptionPlan::firstOrNew(['id' => $id], []);
        $plan->fill($request->all());


        $plan->push();
        $salesNotes = UserRelSubscriptionPlan::setSaleNote($plan);

        if (!empty($salesNotes)) {
            $plan->sale_notes = implode(',', $salesNotes);
            $plan->push();
        }
        return new UserRelSubscriptionPlansResource($plan);

    }


}
