<?php

namespace App\Traits;

use Modules\Catalog\Models\DocumentType;
use Modules\Establishment\Models\Establishment;

trait ReportTrait
{
    public function getTypeDoc($documentType)
    {
        foreach (DocumentType::all() as $item) {
            if (mb_strtoupper($item->description) == $documentType) return $item->id;
        }

        return null;
    }

    public function getEstablishmentId($establishment)
    {
        foreach (Establishment::all() as $item) {
            if (mb_strtoupper($item->description) == $establishment) return $item->id;
        }

        return null;
    }
}
