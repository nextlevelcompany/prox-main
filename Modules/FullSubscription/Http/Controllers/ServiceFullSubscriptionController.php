<?php

namespace Modules\FullSubscription\Http\Controllers;

use Modules\Item\Http\Controllers\ItemController;
use Modules\Item\Http\Requests\ItemRequest;
use Modules\Item\Http\Resources\ItemCollection;
use Modules\Person\Http\Resources\PersonResource;
use Modules\Item\Models\Item;
use Modules\Person\Models\Person;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ServiceFullSubscriptionController extends FullSubscriptionController
{
    public function store(ItemRequest $request)
    {

        $itemController = new ItemController();
        $data = $itemController->store($request);
        //             'id' => $item->id
        if (isset($data['id'])) {
            $item = Item::find($data['id']);
        }
        return $data;
    }

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

    // @todo Cambio a item

    /**
     * @param Request $request
     *
     * @return Builder
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

    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|Response|View
     */
    public function index()
    {
        return view('full_subscription::services.index');

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
