<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Document\Models\Document;
use Modules\Item\Models\Item;
use Modules\Purchase\Models\Purchase;
use Modules\Purchase\Models\PurchaseSettlement;
use Modules\SaleNote\Models\SaleNote;

class Kardex extends ModelTenant
{
    protected $table = 'kardex';

    protected $fillable = [
        'type',
        'date_of_issue',
        'item_id',
        'document_id',
        'purchase_id',
        'purchase_settlement_id',
        'sale_note_id',
        'quantity',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function purchase_settlement(): BelongsTo
    {
        return $this->belongsTo(PurchaseSettlement::class);
    }

    public function sale_note(): BelongsTo
    {
        return $this->belongsTo(SaleNote::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
