<?php

namespace Modules\Production\Http\Controllers;

use App\Helpers\SearchItemHelper;
use App\Http\Controllers\Controller;
use Modules\Item\Models\CatColorsItem;
use Modules\Establishment\Models\Establishment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Item\Models\Item;
use Modules\Production\Exports\PackagingExport;
use Modules\Production\Http\Requests\PackagingRequest;
use Modules\Production\Http\Resources\PackagingCollection;
use Modules\Production\Models\Machine;
use Modules\Production\Models\Packaging;
use Modules\Company\Models\Company;

class PackagingController extends Controller
{
    use InventoryTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('tenant.production.packaging.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('tenant.production.packaging.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PackagingRequest $request
     *
     * @return Response
     */
    public function store(PackagingRequest $request)
    {


        // dd($request->all());
        $result = DB::connection('tenant')->transaction(function () use ($request) {

            $model = new Packaging($request->all());
            $item = Item::find($model->item_id);
            $model->item = $item->toArray();

            $model->user_id = \Auth::user()->id;
            $model->soap_type_id = Company::getCompanySoapTypeId();
            $model->push();


            return [
                'success' => true,
                'message' => 'Ingreso registrado correctamente'
            ];
        });

        return $result;

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
        $machines = Machine::query()->get()->transform(function (Machine $row) {
            return $row->getCollectionData();
        });
        return [
            'warehouses' => $this->optionsWarehouse(),
            'colors' => CatColorsItem::all(),
            'machines' => $machines,
            'establishments' => Establishment::all(),
            'items' => SearchItemHelper::getItemsToPackageZone(),
        ];
    }

    public static function optionsItemProduction()
    {
        return Item::ProductEnded()
            ->get()
            ->transform(function (Item $row) {
                $data = $row->getCollectionData();


                return $data;

            });
    }

    /**
     * @param Request|null $request
     *
     * @return array
     */
    public function searchItems(Request $request)
    {

        $items = SearchItemHelper::getItemsToPackageZone($request);

        return compact('items');
    }

    /*
    public function searchItems(Request $request)
    {
        $search = $request->input('search');

        return [
            'items' => self::optionsItemFullProduction($search, 20),
        ];
    }
    */

    public static function optionsItemFullProduction($search = null, $take = null)
    {
        $query = Item::query()
            ->ProductEnded()
            ->with('item_lots', 'item_lots.item_loteable', 'lots_group');
        if ($search) {
            $query->where('description', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('internal_id', 'like', "%{$search}%");
        }
        if ($take) {
            $query->take($take);
        }
        return $query->get()->transform(function (Item $row) {
            return $row->getCollectionData();
        });
    }

    public function records()
    {
        $records = Packaging::query();
        return new PackagingCollection($records->paginate(config('tenant.items_per_page')));

    }

    /**
     * @param Request $request
     *
     * @return Response|BinaryFileResponse
     */
    public function excel(Request $request)
    {
        // $records = $this->getData($request);
        $records = Packaging::query()->get()->transform(function (Packaging $row) {
            return $row->getCollectionData();
        });

        $packagingExport = new PackagingExport();
        $packagingExport->setCollection($records);
        $filename = 'Reporte de embalaje - ' . date('YmdHis');
        // return $packagingExport->view();
        return $packagingExport->download($filename . '.xlsx');


    }


    public function pdf(Request $request)
    {
        // $records = $this->getData($request);
        $records = Packaging::query()->get()->transform(function (Packaging $row) {
            return $row->getCollectionData();
        });

        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $pdf = PDF::loadView('tenant.production.packaging.partial.export',
            compact(
                'records'
            ))
            ->setPaper('a4', 'landscape');


        $filename = 'Reporte de embalaje - ' . date('YmdHis');
        return $pdf->stream($filename . '.pdf');
    }
}
