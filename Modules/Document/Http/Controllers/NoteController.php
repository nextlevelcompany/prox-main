<?php

namespace Modules\Document\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Document\Models\Document;
use Modules\Company\Models\Configuration;

class NoteController extends Controller
{
    public function create($document_id)
    {
        $document_affected = Document::filterDataDocumentAffected()->find($document_id);
        $configuration = Configuration::first();

        return view('tenant.documents.note', compact('document_affected', 'configuration'));
    }

    public function record($document_id)
    {
        $record = Document::filterDataDocumentAffected()->find($document_id);

        return $record;
    }

    public function hasDocuments($document_id)
    {
        $record = Document::wherehas('affected_documents')->find($document_id);

        if ($record) {
            return [
                'success' => true,
                'data' => $record->affected_documents->transform(function ($row, $key) {
                    return [
                        'id' => $row->id,
                        'document_id' => $row->document_id,
                        'document_type_description' => $row->document->document_type->description,
                        'description' => $row->document->number_full,
                    ];
                })
            ];
        }

        return [
            'success' => false,
            'data' => []
        ];
    }
}
