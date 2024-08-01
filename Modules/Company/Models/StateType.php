<?php

namespace Modules\Company\Models;

use App\Models\Tenant\ModelTenant;

class StateType extends ModelTenant
{
    public $incrementing = false;
    public $timestamps = false;

    public static function getDataApiApp()
    {
        $states = self::get();

        return $states->push([
            'id' => 'all',
            'description' => 'Todos',
        ]);
    }

}
