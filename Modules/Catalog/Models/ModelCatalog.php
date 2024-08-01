<?php

namespace Modules\Catalog\Models;

use App\Models\Tenant\ModelTenant;

class ModelCatalog extends ModelTenant
{
    public function scopeWhereActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrderByDescription($query)
    {
        return $query->orderBy('description');
    }
}
