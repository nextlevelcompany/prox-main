<?php

namespace Modules\Hotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Hotel\Models\HotelRate;
use Modules\Hotel\Http\Requests\HotelRateRequest;

class HotelRateController extends Controller
{
	public function index()
	{
		$rates = HotelRate::orderBy('id', 'DESC')
			->get();

		return view('tenant.hotel.rates.index', compact('rates'));
	}

	public function store(HotelRateRequest $request)
	{
		$rate = HotelRate::create($request->only('description', 'active'));

		return response()->json([
			'success' => true,
			'data'    => $rate
		], 200);
	}

	public function update(HotelRateRequest $request, $id)
	{
		$rate = HotelRate::findOrFail($id);
		$rate->fill($request->only('description', 'active'));
		$rate->save();

		return response()->json([
			'success' => true,
			'data'    => $rate
		], 200);
	}

	public function destroy($id)
	{
		try {
			HotelRate::where('id', $id)
				->delete();

			return response()->json([
				'success' => true,
				'message' => 'Información actualizada'
			], 200);
		} catch (\Throwable $th) {
			return response()->json([
				'success'    => false,
				'message'    => 'Ocurrió un error al procesar su petición. Detalles: ' . $th->getMessage()
			], 500);
		}
	}
}
