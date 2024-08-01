<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Catalog\Models\DocumentType;
use Modules\User\Models\User;

class Guide extends ModelTenant
{
    protected $fillable = [
        'external_id',
        'user_id',
        'soap_type_id',
        'document_type_id',
        'series',
        'number',
        'date_of_issue',
        'time_of_issue',
        'inventory_transaction_id',
        'guideable_id',
        'guideable_type',
        'warehouse_id',
        'observations'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function guideable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function inventory_transaction()
    {
        return $this->belongsTo(InventoryTransaction::class, 'inventory_transaction_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(GuideItem::class);
    }

    public function scopeWhereInventoryTransaction($query, $inventory_transaction_id)
    {
        if($inventory_transaction_id === 'all') {
            return $query;
        }
        return $query->where('inventory_transaction_id', $inventory_transaction_id);
    }
}
