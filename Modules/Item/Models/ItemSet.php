<?php

namespace Modules\Item\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemSet extends ModelTenant
{
    protected $fillable = [
        'item_id',
        'individual_item_id',
        'quantity',
        'unit_price'
    ];

    /**
     * @return BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return BelongsTo
     */
    public function individual_item()
    {
        return $this->belongsTo(Item::class, 'individual_item_id');
    }

    /**
     * @return BelongsTo
     */
    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'individual_item_id');
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return (int)$this->item_id;
    }

    /**
     * @param int $item_id
     *
     * @return ItemSet
     */
    public function setItemId($item_id = 0)
    {
        $this->item_id = (int)$item_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getIndividualItemId()
    {
        return (int)$this->individual_item_id;
    }

    /**
     * @param int $individual_item_id
     *
     * @return ItemSet
     */
    public function setIndividualItemId($individual_item_id = 0)
    {
        $this->individual_item_id = (int)$individual_item_id;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return (float)$this->quantity;
    }

    /**
     * @param float $quantity
     *
     * @return ItemSet
     */
    public function setQuantity($quantity = 0)
    {
        $this->quantity = (float)$quantity;
        return $this;
    }

}