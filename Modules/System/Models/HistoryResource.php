<?php

namespace Modules\System\Models;

use App\Models\ModelSystem;

class HistoryResource extends ModelSystem
{
    protected $fillable = [
        'cpu_percent',
        'memory_total',
        'memory_free',
        'memory_used',
    ];
}
