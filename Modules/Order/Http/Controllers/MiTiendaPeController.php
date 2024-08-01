<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Catalog\Models\CurrencyType;
use Modules\Establishment\Models\Establishment;
use Modules\Establishment\Models\Series;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Order\Imports\MiTiendaPeImport;
use Modules\Order\Models\ConfigurationMiTiendaPe;

class MiTiendaPeController extends Controller
{
    use FinanceTrait;

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $import = new MiTiendaPeImport();
                $import->import($request->file('file'), null, Excel::XLSX);
                $data = $import->getData();
                $content = $import->getProcess();
                return [
                    'success' => true,
                    'message' => __('app.actions.upload.success'),
                    'data' => $data,
                    // 'content' => $content,
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' => __('app.actions.upload.error'),
        ];
    }

    public function index()
    {
        // use Modules\Establishment\Models\Establishment;
        $establishments = Establishment::all()->transform(function (Establishment $row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });
        return view('order::mi_tienda_pe.index', compact('establishments'));
    }

    public function store(Request $request)
    {
        $configurationMiTienda = ConfigurationMiTiendaPe::first();
        if (empty($configurationMiTienda)) {
            $configurationMiTienda = new ConfigurationMiTiendaPe();
        }
        $configurationMiTienda->fill($request->all())->push();
        return compact('configurationMiTienda');
    }

    public function getData(Request $request)
    {
        $configurationMiTienda = ConfigurationMiTiendaPe::first();
        if (empty($configurationMiTienda)) {
            $configurationMiTienda = new ConfigurationMiTiendaPe();
        }


        $seriesBoleta = Series::where([
            'establishment_id' => $configurationMiTienda->establishment_id,
            'document_type_id' => '03',
        ])->get();
        $seriesInvoice = Series::where([
            'establishment_id' => $configurationMiTienda->establishment_id,
            'document_type_id' => '01',
        ])->get();
        $currencys = func_get_table_currency_types();
        $payment_destinations = [];
        $payment_destinations_temp = $this->getPaymentDestinations()->transform(function ($row) use (&$payment_destinations) {
            if (($row['id'] != 'cash')) {
                $payment_destinations[] = $row;
            }
            return $row;
        });
        $configurationMiTienda = $configurationMiTienda->toArray();


        $data = [

            'series_order' => [],
            'series_document_ft' => $seriesInvoice,
            'series_document_bt' => $seriesBoleta,
            'payment_destinations' => $payment_destinations,
            'currency_types' => $currencys,

        ];
        return compact('configurationMiTienda', 'data');
    }

    public function tables(Request $request)
    {

        $seriesBoleta = Series::where([
            'establishment_id' => $request->establishment_id,
            'document_type_id' => '03',
        ])->get();
        $seriesInvoice = Series::where([
            'establishment_id' => $request->establishment_id,
            'document_type_id' => '01',
        ])->get();
        $currencys = func_get_table_currency_types();
        $payment_destinations = [];
        $payment_destinations_temp = $this->getPaymentDestinations()->transform(function ($row) use (&$payment_destinations) {
            if (($row['id'] != 'cash')) {
                $payment_destinations[] = $row;
            }
            return $row;
        });


        $data = [

            'series_order' => [],
            'series_document_ft' => $seriesInvoice,
            'series_document_bt' => $seriesBoleta,
            'payment_destinations' => $payment_destinations,
            'currency_types' => $currencys,

        ];

        return compact('data');


    }


}
