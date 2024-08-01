<?php

namespace Modules\GlobalFactoring\Models;

use App\Models\Tenant\ModelTenant;

class ApiState extends ModelTenant
{
    protected $table = 'cat_global_factoring_api_states';

    protected $fillable = ['name'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
