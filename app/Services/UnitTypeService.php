<?php

namespace App\Services;

use Modules\Catalog\Models\UnitType;

class UnitTypeService
{
    public function getDescription($value)
    {
        $row = UnitType::where('id', $value)->first();
        return ($row) ? $row->description : 'NIU';
    }
}
