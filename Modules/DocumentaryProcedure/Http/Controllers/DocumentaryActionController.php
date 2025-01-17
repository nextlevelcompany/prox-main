<?php

namespace Modules\DocumentaryProcedure\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\DocumentaryProcedure\Models\DocumentaryAction;
use Modules\DocumentaryProcedure\Http\Requests\ActionRequest;

class DocumentaryActionController extends Controller
{
	public function index()
	{
		$actions = DocumentaryAction::orderBy('id', 'DESC');
		if (request()->ajax()) {
			$filter = request('name');
			if ($filter) {
				$actions = $actions->where('name', 'like', "%$filter%")->get();
			}

			return response()->json(['data' => $actions], 200);
		}
		$actions = $actions->get();

		return view('tenant.documentary_procedure.actions', compact('actions'));
	}

	public function store(ActionRequest $request)
	{
		$action = DocumentaryAction::create($request->only('name', 'description', 'active'));

		return response()->json([
			'data'    => $action,
			'message' => 'Acción guardada de forma correcta.',
			'succes'  => true,
		], 200);
	}

	public function update(ActionRequest $request, $id)
	{
		$action = DocumentaryAction::findOrFail($id);
		$action->fill($request->only('name', 'description', 'active'));
		$action->save();

		return response()->json([
			'data'    => $action,
			'message' => 'Acción actualizada de forma correcta.',
			'succes'  => true,
		], 200);
	}

	public function destroy($id)
	{
		try {
			$action = DocumentaryAction::findOrFail($id);
			$action->delete();

			return response()->json([
				'data'    => null,
				'message' => 'Acción eliminada de forma correcta.',
				'succes'  => true,
			], 200);
		} catch (\Throwable $th) {
			return response()->json([
				'success' => false,
				'data'    => 'Ocurrió un error al procesar su petición. Detalles: ' . $th->getMessage()
			], 500);
		}
	}
}
