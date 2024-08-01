<?php

namespace Modules\Inventory\Models;

use Modules\Company\Models\Company;
use Modules\Company\Models\Configuration;
use App\Models\Tenant\ModelTenant;
use Modules\User\Models\User;

class InventoryTransfer extends ModelTenant
{
    protected $table = 'inventories_transfer';

    protected $fillable = [
        'external_id',
        'user_id',
        'soap_type_id',
        'document_type_id',
        'series',
        'number',
        'description',
        'warehouse_id',
        'warehouse_destination_id',
        'quantity',
        'filename'
    ];
    protected $casts = [
        'warehouse_id' => 'int',
        'warehouse_destination_id' => 'int',
        'user_id' => 'int',
        'quantity' => 'float'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'inventories_transfer_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse_destination()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'inventories_transfer_id');
    }

    public function inventory_transfer_item()
    {
        return $this->hasMany(InventoryTransferItem::class);
    }


    public function getPdfData()
    {

        $data = [];
        $data['serie'] = $this->series;
        $data['number'] = $this->number;
        $data['document_type'] = "NOTA DE TRASLADO";
        $data['motivo'] = $this->description;
        $data['created_at'] = $this->created_at;
        $data['quantity'] = $this->quantity;
        $data['warehouse_from'] = $this->warehouse;
        $data['warehouse_to'] = $this->warehouse_destination;
        $data['user'] = $this->user;
        $data['inventories'] = $this->inventories;
        $data['item_transfers'] = $this->inventory_transfer_item->transform(function($o) {
            if($o->item_lots_group_id != null) {
                return [
                    'item_id' => $o->item_lots_group->item_id,
                    'code' => $o->item_lots_group->code,
                ];
            }
            if($o->item_lot_id != null) {
                return [
                    'item_id' => $o->item_lot->item_id,
                    'code' => $o->item_lot->series,
                ];
            }
        });;
        $data['configuration'] = Configuration::first();
        $data['company'] = Company::active();

        return $data;
    }

}
