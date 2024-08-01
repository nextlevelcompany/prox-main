<?php

namespace Modules\Hotel\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Hotel\Models\HotelCategory;
use Modules\Hotel\Http\Requests\HotelCategoryRequest;

class HotelCategoryController extends Controller
{
	public function index()
	{
		$categories = HotelCategory::orderBy('id', 'DESC')
			->get();

		return view('tenant.hotel.categories.index', compact('categories'));
	}

	public function store(HotelCategoryRequest $request)
	{
		$rate = HotelCategory::create($request->only('description', 'active'));

		return response()->json([
			'success' => true,
			'data'    => $rate
		], 200);
	}

	public function update(HotelCategoryRequest $request, $id)
	{
		$rate = HotelCategory::findOrFail($id);
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
			HotelCategory::where('id', $id)
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
