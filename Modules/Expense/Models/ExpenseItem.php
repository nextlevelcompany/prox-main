<?php

namespace Modules\Expense\Models;

use App\Models\Tenant\ModelTenant;
use App\Traits\AttributePerItems;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseItem extends ModelTenant
{
    use AttributePerItems;

    public $timestamps = false;

    protected $fillable = [
        'expense_id',
        'description',
        'total',
    ];

    protected $casts = [
        'expense_id' => 'int',
        'total' => 'float'
    ];

    /**
     * @return BelongsTo
     */
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

}
