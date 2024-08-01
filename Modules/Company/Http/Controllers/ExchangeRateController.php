<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Http\Resources\ExchangeRateCollection;
use Modules\Company\Models\ExchangeRate;

class ExchangeRateController extends Controller
{
    public function records()
    {
        $records = ExchangeRate::orderBy('date', 'desc')->get();

        return new ExchangeRateCollection($records);
    }

    public function store(Request $request)
    {
        $exchangeRates = $request->all();
        foreach ($exchangeRates as $exchangeRate) {
        	ExchangeRate::create($exchangeRate);
        }
        return [
            'success' => true,
            'message' => 'Tipos de cambio registrados con éxito'
        ];
    }
}
