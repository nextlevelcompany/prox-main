<?php

namespace Modules\Subscription\Http\Controllers;

use App\Helpers\SearchCustomerHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Subscription\Http\Requests\PaymentsSubscriptionRequest;
use Modules\Subscription\Http\Resources\UserRelSubscriptionPlansCollection;
use Modules\Subscription\Http\Resources\UserRelSubscriptionPlansResource;
use Modules\Subscription\Models\CatPeriod;
use Modules\Subscription\Models\SubscriptionPlan;
use Modules\Subscription\Models\UserRelSubscriptionPlan;

class PaymentsSubscriptionController extends SubscriptionController
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
        return new UserRelSubscriptionPlansResource(UserRelSubscriptionPlan::findOrFail($request->person));
    }

    public function Records(Request $request)
    {
        $records = UserRelSubscriptionPlan::query();
        if ($request->has('column') && !empty($request->column)) {
            $records->where($request->column, 'like', "%{$request->value}%");
        }

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

        return compact(
            'periods',
            'customers',
            'startDate',
            'plans'

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
        return view('tenant.subscription.payments.index');
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

    public function searchCustomer(Request $request)
    {
        $customers = SearchCustomerHelper::getSubscriptionCustomers($request);

        return ['customers' => $customers];

    }

    public function store(PaymentsSubscriptionRequest $request)
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
