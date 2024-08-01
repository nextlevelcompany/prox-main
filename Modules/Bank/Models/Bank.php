<?php

namespace Modules\Bank\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends ModelTenant
{
    protected $casts = [
        'active' => 'bool'
    ];
    protected $fillable = [
        'description',
        'active'
    ];

    /**
     * @return HasMany
     */
    public function bank_accounts()
    {
        return $this->hasMany(BankAccount::class);
    }
}
