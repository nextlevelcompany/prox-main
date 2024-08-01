<?php

namespace Modules\Production\Http\Controllers;

use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use Exception;
use Modules\Catalog\Models\AffectationIgvType;
use Modules\Catalog\Models\AttributeType;
use Modules\Catalog\Models\ChargeDiscountType;
use Modules\Catalog\Models\OperationType;
use Modules\Catalog\Models\PriceType;
use Modules\Catalog\Models\SystemIscType;
use Modules\Catalog\Models\UnitType;
use Modules\Company\Models\Company;
use Modules\Company\Models\Configuration;
use Modules\Establishment\Models\Establishment;
use Modules\Inventory\Models\Warehouse;
use Modules\Item\Models\Item;
use Modules\Person\Models\Person;
use App\Traits\OfflineTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Expense\Exports\ExpenseExport;
use Modules\Expense\Models\Expense;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Production\Http\Requests\MachineRequest;
use Modules\Production\Http\Requests\MachineTypeRequest;
use Modules\Production\Http\Resources\MachineCollection;
use Modules\Production\Http\Resources\MachineTypeCollection;
use Modules\Production\Models\Machine;
use Modules\Production\Models\MachineType;

class MachineController extends Controller
{
    use InventoryTrait;
    use FinanceTrait;
    use OfflineTrait;

    public static function merge_inputs($inputs)
    {
        $company = Company::active();

        $values = [
            'user_id' => auth()->id(),
            'state_type_id' => $inputs['id'] ? $inputs['state_type_id'] : '05',
            'soap_type_id' => $company->soap_type_id,
            'external_id' => $inputs['id'] ? $inputs['external_id'] : Str::uuid()->toString(),
            'supplier' => PersonInput::set($inputs['supplier_id']),
        ];

        $inputs->merge($values);

        return $inputs->all();
    }

    public function columns()
    {
        return [

            'name' => 'Nombre',
            'brand' => 'Marca',
            'model' => 'Modelo',
            'closing_force' => 'Fuerza de cierre',
        ];
    }

    public function columnsType()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('tenant.production.machine.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexType()
    {
        return view('tenant.production.machine.index_type');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function create($id = null)
    {
        return view('tenant.production.machine.form', compact('id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function createType($id = null)
    {
        return view('tenant.production.machine.form_type', compact('id'));
    }

    public function saveType(MachineTypeRequest $request)
    {

        $e = null;
        if ($request->has('id')) {
            $e = MachineType::find($request->id);
        }
        if (empty($e)) {
            $e = new MachineType($request->all());
        }
        $e->fill($request->all());
        $e->push();
        return [
            'success' => true,
            'message' => 'Datos guardada de forma correcta.',
            'data' => $e->toArray()
        ];


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MachineRequest $request
     *
     * @return Response
     */
    public function store(MachineRequest $request)
    {

        $model = null;
        if ($request->has('id')) {
            $model = Machine::find($request->id);
        }
        if (empty($model)) {
            $model = new Machine($request->all());
        }
        $model->fill($request->all());
        $model->save();

        return [
            'success' => true,
            'message' => 'Datos guardada de forma correcta.',
            'data' => $model->toArray()
        ];

    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return view('production::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return view('production::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /*
    public function tables() {
        $warehouses = Warehouse::all();
        return compact('warehouses');

    }
    */

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function tables()
    {
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
        $machine_types = MachineType::where('active', 1)->get();


        return compact(
            'establishment',
            'machine_types'
        );
    }

    public static function optionsItemSupplies()
    {
        // $ids = ItemSupply::select('individual_item_id')->distinct()->pluck('individual_item_id');
        return Item::ProductSupply()
            ->get()
            ->transform(function (Item $row) {
                return $row->getCollectionData();
            });
        return collect($records)->transform(function ($row) {
            return [
                'id' => $row->id,
                'description' => $row->description
            ];
        });
    }

    public function table($table)
    {
        switch ($table) {
            case 'suppliers':

                $suppliers = Person::whereType('suppliers')->orderBy('name')->get()->transform(function ($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number . ' - ' . $row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code
                    ];
                });
                return $suppliers;
            case 'items':

                // $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

                $items = self::optionsItemSupplies();
                /*
                $items = Item::orderBy('description')->whereIsActive()
                    // ->ForProduction()
                    // ->with(['warehouses' => function($query) use($warehouse){
                    //     return $query->where('warehouse_id', $warehouse->id);
                    // }])
                    ->take(20)->get();

                */
                //$this->ReturnItem($items);

                return $items;

                break;
            default:
                return [];

                break;
        }
    }

    public function ReturnItem(&$item)
    {
        $configuration = Configuration::first();
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

        $item->transform(function ($row) use ($configuration, $warehouse) {
            return $row->getDataToItemModal($warehouse, false, true);
        });
    }

    public function records()
    {
        $records = Machine::query();
        return new MachineCollection($records->paginate(config('tenant.items_per_page')));

    }

    public function recordsType()
    {
        $records = MachineType::query();
        return new MachineTypeCollection($records->paginate(config('tenant.items_per_page')));

    }

    public function record($id)
    {
        $record = Machine::findOrFail($id);
        return $record->getCollectionData();
    }

    public function recordType($id)
    {
        $record = MachineType::findOrFail($id);
        return $record->toArray();
    }

    public function voided($record)
    {
        try {
            $expense = Expense::findOrFail($record);
            $expense->state_type_id = 11;
            $expense->save();
            return [
                'success' => true,
                'data' => [
                    'id' => $expense->id,
                ],
                'message' => 'Gasto anulado exitosamente',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [
                    'id' => $record,
                ],
                'message' => 'Falló al anular',
            ];
        }
    }

    public function excel(Request $request)
    {

        $records = Expense::where($request->column, 'like', "%{$request->value}%")
            ->whereTypeUser()
            ->latest()
            ->get();
        // dd($records);

        $establishment = auth()->user()->establishment;
        $balance = new ExpenseExport();
        $balance
            ->records($records)
            ->establishment($establishment);

        // return $balance->View();
        return $balance->download('Expense_' . Carbon::now() . '.xlsx');

    }

    public function item_tables()
    {
        $items = $this->table('items');
        // $items = SearchItemHelper::getItemToContract();
        $categories = [];
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();
        $unit_types = UnitType::all();

        $operation_types = OperationType::whereActive()->get();
        $is_client = $this->getIsClient();

        return compact(
            'items',
            'categories',
            'affectation_igv_types',
            'system_isc_types',
            'price_types',
            'discount_types',
            'charge_types',
            'attribute_types',
            'unit_types',
            'operation_types',
            'is_client'
        );
    }
}
