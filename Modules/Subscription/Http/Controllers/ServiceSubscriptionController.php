<?php

namespace Modules\Subscription\Http\Controllers;

use Modules\Item\Http\Controllers\ItemController;
use Modules\Item\Http\Requests\ItemRequest;
use Modules\Item\Http\Resources\ItemCollection;
use Modules\Person\Http\Resources\PersonResource;
use Modules\Item\Models\Item;
use Modules\Person\Models\Person;
use Illuminate\Http\Request;

class ServiceSubscriptionController extends SubscriptionController
{
    public function index()
    {
        return view('subscription::services.index');
    }

    public function store($request)
    {
        $itemController = new ItemController();
        $data = $itemController->store($request);
        //             'id' => $item->id
        if (isset($data['id'])) {
            $item = Item::find($data['id']);
        }
        return $data;
    }


    // @todo Cambio a item

    public function Columns()
    {
        return [
            // 'name' => 'Nombre',
            // 'number' => 'Número',
            // 'document_type' => 'Tipo de documento'

            // 'index' => "#",
            'internal_id' => "Cód. Interno",
            'unit_type_id' => "Unidad",
            'name' => "Nombre",
            'description' => "Descripción",
            'model' => "Modelo",
            'brand' => "Marca",
            // 'item_code' => "Cód. SUNAT",
            'stock' => "Stock",
            'purchase_unit_price' => "P.Unitario (Venta)",
            'purchase_has_igv_description' => "P.Unitario (Compra)",
            'has_igv_description' => "Tiene Igv (Venta)",
// '' =>"Tiene Igv (Compra)",
        ];
    }

    // @todo Cambio a item

    public function Records(Request $request)
    {

        $records = $this->getServiceRecords($request);

        return new ItemCollection($records->paginate(config('tenant.items_per_page')));
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getServiceRecords(Request $request)
    {

        $records = Item::whereTypeUser()->whereNotIsSet();
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
        $records->whereService();
        $filter = 'description';

        if ($request->has('column')) {
            // $filter = 'id';

            if ($request->column != 'index') {
                $filter = $request->column;
            }

        }
        return $records->orderBy($filter);

    }

    public function Tables()
    {
        return $this->Tables();

    }


    public function Record(Request $request)
    {
        /*@todo colocar como servicio*/
        $record = new PersonResource(Person::findOrFail($request->person));

        return $record;
    }

}
