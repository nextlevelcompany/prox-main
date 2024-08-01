<?php

namespace Modules\Order\Services;

use Modules\Catalog\Models\District;

class AddressFullService
{

    public static function getDescription($district_id)
    {

        $district = District::findOrFail($district_id);

        return "{$district->province->department->description} - {$district->province->description} - {$district->description}";

    }


}
