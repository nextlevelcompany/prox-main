<?php

namespace Modules\DocumentaryProcedure\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\DocumentaryProcedure\Models\DocumentaryDocument;
use Modules\DocumentaryProcedure\Http\Requests\DocumentRequest;

class DocumentaryDocumentController extends Controller
{
	public function index()
	{
		$documents = DocumentaryDocument::orderBy('id', 'DESC');
		if (request()->ajax()) {
			$filter = request('name');
			if ($filter) {
				$documents = $documents->where('name', 'like', "%$filter%")->get();
			}

			return response()->json(['data' => $documents], 200);
		}
		$documents = $documents->get();

		return view('tenant.documentary_procedure.documents', compact('documents'));
	}

	public function store(DocumentRequest $request)
	{
		$document = DocumentaryDocument::create($request->only('name', 'description', 'active'));

		return response()->json([
            'data' => $document,
            'message' => 'Documento guardada de forma correcta.',
            'succes' => true,
        ], 200);
	}

	public function update(DocumentRequest $request, $id)
	{
		$document = DocumentaryDocument::findOrFail($id);
		$document->fill($request->only('name', 'description', 'active'));
		$document->save();

		return response()->json([
            'data' => $document,
            'message' => 'Documento actualizada de forma correcta.',
            'succes' => true,
        ], 200);
	}

	public function destroy($id)
	{
		try {
			$document = DocumentaryDocument::findOrFail($id);
			$document->delete();

			return response()->json([
                'data' => null,
                'message' => 'Documento eliminada de forma correcta.',
                'succes' => true,
            ], 200);
		} catch (\Throwable $th) {
			return response()->json([
				'success' => false,
				'data'    => 'Ocurrió un error al procesar su petición. Detalles: ' . $th->getMessage()
			], 500);
		}
	}
}
