<?php

namespace Modules\Company\Models;

use App\Models\Tenant\ModelTenant;

class Task extends ModelTenant
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class',
        'execution_time',
        'output'
    ];
}
