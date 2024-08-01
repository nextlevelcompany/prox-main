<?php

namespace Modules\Expense\Models;

use Modules\Item\Models\Item;
use App\Models\Tenant\ModelTenant;

class ExpenseType extends ModelTenant
{
    protected $fillable = [
        'description',
    ];

}
