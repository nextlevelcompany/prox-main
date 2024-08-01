<?php

namespace Modules\System\Models;

use App\Models\ModelSystem;

class PlanDocument extends ModelSystem
{
    protected $table = "plan_documents";

    protected $fillable = [
        'description',
    ];

    public $timestamps = false;
}
