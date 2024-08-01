<?php

namespace Modules\Subscription\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Subscription\Http\Resources\SubscriptionPlansCollection;
use Modules\Subscription\Models\SubscriptionGrade;
use Modules\Subscription\Models\Tenant\SubscriptionPlan;
use Modules\Subscription\Models\SubscriptionSection;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('subscription::index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function payments_index()
    {
        return view('tenant.subscription.payments.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function plans_index()
    {
        return view('tenant.subscription.plans.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('subscription::create');
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

    public function destroy($id)
    {
        $record = SubscriptionPlan::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Matrícula eliminada con éxito'
        ];

    }


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
        $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
        $grades = SubscriptionGrade::all();
        $sections = SubscriptionSection::all();

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
            'grades',
            'sections',
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


    public function indexGradeSection()
    {
        return view('tenant.subscription.grade_section.index');
    }

}
