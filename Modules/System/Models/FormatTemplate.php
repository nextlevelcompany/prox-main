<?php

namespace Modules\System\Models;

use App\Models\ModelSystem;

class FormatTemplate extends ModelSystem
{
    protected $table = 'format_templates';

    protected $fillable = [
    	'id',
    	'formats'
    ];
}
