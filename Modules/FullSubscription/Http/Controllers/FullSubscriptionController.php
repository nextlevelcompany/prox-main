<?php

namespace Modules\FullSubscription\Http\Controllers;

use Modules\Catalog\Models\AffectationIgvType;
use Modules\Catalog\Models\Country;
use Modules\Catalog\Models\CurrencyType;
use Modules\Catalog\Models\Department;
use Modules\Catalog\Models\District;
use Modules\Catalog\Models\IdentityDocumentType;
use Modules\Catalog\Models\Province;
use Modules\Catalog\Models\UnitType;
use Modules\Company\Models\Configuration;
use Modules\Item\Models\Item;
use App\Models\Tenant\PaymentMethodType;
use Modules\Person\Models\PersonType;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\FullSubscription\Http\Resources\SubscriptionPlansCollection;
use Modules\FullSubscription\Models\Tenant\SubscriptionPlan;

class FullSubscriptionController extends Controller
{
    public function index()
    {
        return view('tenant.full_subscription.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|Response|View
     */
    public function payments_index()
    {
        return view('tenant.subscription.payments.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|Response|View
     */
    public function plans_index()
    {
        return view('tenant.full_subscription.plans.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|Application|Response|View
     */
    public function create()
    {
        return view('full_subscription::create');
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Factory|Application|Response|View
     */
    public function destroy($id)
    {

        $record = SubscriptionPlan::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Matrícula eliminada con éxito'
        ];

    }


    /**
     * @param Request $request
     *
     * @return Builder
     */
    public function getServiceRecords(Request $request)
    {

        $records = Item::whereTypeUser()->whereNotIsSet()->whereService();
        switch ($request->column) {
            case 'brand':
                $records->whereHas('brand', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->value}%");
                });
                break;
            case 'active':
                $records->whereIsActive();
                break;

            case 'inactive':
                $records->whereIsNotActive();
                break;

            default:
                if ($request->has('column')) {
                    $filter = 'id';
                    if ($request->column != 'index') $filter = $request->column;
                    $records->where($filter, 'like', "%{$request->value}%");
                }
                break;
        }
        $filter = 'description';

        if ($request->has('column') && $request->column != 'index') {
            $filter = $request->column;

        }
        return $records->orderBy($filter);

    }

    public function plansColumns()
    {
        return [
            'cat_period_id' => 'Periodos',
            'name' => 'Descripción',
            'description' => 'Nombre',
        ];

    }

    public function plansRecord(Request $request)
    {
        $record = new SubscriptionPlansCollection(SubscriptionPlan::findOrFail($request->person));
        return $record;
    }

    public function plansRecords(Request $request)
    {
        $type = 'customers';
        $records = SubscriptionPlan::where($request->column, 'like', "%{$request->value}%")
            // ->where('type', $type)
            ->orderBy('name');

        return new SubscriptionPlansCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function Tables()
    {

        $countries = func_get_table_countries();
        $departments = Department::whereActive()->orderByDescription()->get();
        $provinces = Province::whereActive()->orderByDescription()->get();
        $districts = District::whereActive()->orderByDescription()->get();
        $identity_document_types = func_get_table_identity_document_types();
        $person_types = PersonType::get();
        $locations = $this->getLocationCascade();
        // $configuration = Configuration::first();
        // $api_service_token = $configuration->token_apiruc == 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;
        $api_service_token = Configuration::getApiServiceToken();

        $unit_types = UnitType::whereActive()->orderByDescription()->get();
        $currency_types = func_get_table_currency_types();
        $affectation_igv_types = AffectationIgvType::whereActive()->get();

        $payments_credit = PaymentMethodType::select('id')->NonCredit()->get()->toArray();
        $payments_credit = PaymentMethodType:: getPaymentMethodTypes($payments_credit);
        $startDate = Carbon::createFromFormat('Y-m-d', '2022-01-01')->format('Y-m-d');

        return compact('unit_types',
            'currency_types',
            'affectation_igv_types',
            'startDate',
            'countries',
            'departments',
            'provinces',
            'districts',
            'identity_document_types',
            'locations',
            'person_types',
            'payments_credit',
            'api_service_token');
    }

    /**
     * Devuelve un array para Privincia, distrito
     *
     * @return array
     */
    public function getLocationCascade()
    {
        $locations = [];
        $departments = Department::where('active', true)->get();
        foreach ($departments as $department) {
            $children_provinces = [];
            foreach ($department->provinces as $province) {
                $children_districts = [];
                foreach ($province->districts as $district) {
                    $children_districts[] = [
                        'value' => $district->id,
                        'label' => $district->id . " - " . $district->description
                    ];
                }
                $children_provinces[] = [
                    'value' => $province->id,
                    'label' => $province->description,
                    'children' => $children_districts
                ];
            }
            $locations[] = [
                'value' => $department->id,
                'label' => $department->description,
                'children' => $children_provinces
            ];
        }

        return $locations;
    }


}
