<?php

namespace Modules\Document\Observers;

use Illuminate\Support\Str;
use Modules\Company\Models\Company;
use Modules\Document\Models\Document;
use Modules\Establishment\Models\SeriesConfiguration;

class DocumentObserver
{
    public function creating(Document $document)
    {
        $company = Company::query()->first();

        $document->user_id = auth()->id();
        $document->external_id = Str::uuid()->toString();
        $document->soap_type_id = $company->soap_type_id;
        $document->state_type_id = '01';

        $number = $this->getNewNumber($document);
        $filename = join('-', [$company->number, $document->document_type_id, $document->series, $number]);
        $document->number = $number;
        $document->filename = $filename;
        $document->unique_filename = $document->filename; //campo único para evitar duplicados
    }

    private function getNewNumber($document)
    {
        if ($document->number === '#') {
            $record = Document::query()
                ->select('number')
                ->where('soap_type_id', $document->soap_type_id)
                ->where('document_type_id', $document->document_type_id)
                ->where('series', $document->series)
                ->orderBy('number', 'desc')
                ->first();

            if ($record) {
                return $record->number + 1;
            } else {
                $series_configuration = SeriesConfiguration::query()
                    ->where('document_type_id', $document->document_type_id)
                    ->where('series', $document->series)
                    ->first();
                if ($series_configuration) {
                    return $series_configuration->number;
                }
            }
            return 1;
        }

        return $document->number;
    }
}
