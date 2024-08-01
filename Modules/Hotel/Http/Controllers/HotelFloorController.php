<?php

namespace Modules\Hotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Hotel\Models\HotelFloor;
use Modules\Hotel\Http\Requests\HotelFloorRequest;

class HotelFloorController extends Controller
{
	public function index()
	{
		$floors = HotelFloor::orderBy('id', 'DESC')
			->get();

		return view('tenant.hotel.floors.index', compact('floors'));
	}

	public function store(HotelFloorRequest $request)
	{
		$rate = HotelFloor::create($request->only('description', 'active'));

		return response()->json([
			'success' => true,
			'data'    => $rate
		], 200);
	}

	public function update(HotelFloorRequest $request, $id)
	{
		$rate = HotelFloor::findOrFail($id);
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
			HotelFloor::where('id', $id)
				->delete();

			return response()->json([
				'success' => true,
				'message' => 'Información actualizada'
			], 200);
		} catch (\Throwable $th) {
			return response()->json([
				'success' => false,
				'data'    => 'Ocurrió un error al procesar su petición. Detalles: ' . $th->getMessage()
			], 500);
		}
	}
}
