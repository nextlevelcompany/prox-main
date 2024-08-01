<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\TechnicalServiceItem;
use Modules\Establishment\Models\Establishment;
use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Builder;

class Warehouse extends ModelTenant
{
    protected $fillable = [
        'establishment_id',
        'description',
    ];

    public function inventory_kardex()
    {
        return $this->hasMany(InventoryKardex::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function technical_service_item()
    {
        return $this->hasMany(TechnicalServiceItem::class, 'warehouse_id');
    }

    public function getEstablishmentId(): int
    {
        return $this->establishment_id;
    }

    /**
     * @param int $establishment_id
     *
     * @return Warehouse
     */
    public function setEstablishmentId(int $establishment_id): Warehouse
    {
        $this->establishment_id = $establishment_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Warehouse
     */
    public function setDescription(string $description): Warehouse
    {
        $this->description = $description;
        return $this;
    }


    /**
     *
     * Data para filtros - select
     *
     * @return array
     */
    public static function getDataForFilters()
    {
        return self::with(['establishment' => function ($query) {
            $query->whereFilterWithOutRelations()
                ->select(['id', 'description']);
        }])
            ->get()
            ->transform(function ($row) {
                return $row->getRowForFilter();
            });
    }


    /**
     *
     * Campos para filtros - select
     *
     * @return array
     */
    public function getRowForFilter()
    {
        return [
            'id' => $this->id,
            'establishment_id' => $this->establishment_id,
            'warehouse_description' => $this->description,
            'establishment_description' => $this->establishment->description,
        ];
    }


    /**
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeSelectBasicColumns($query)
    {
        return $query->select([
            'id',
            'description',
        ]);
    }


    /**
     *
     * Obtener id del almacen
     *
     * @param Builder $query
     * @param int $establishment_id
     * @return Builder
     */
    public function scopeGetWarehouseId($query, $establishment_id = null)
    {
        $establishment_id = $establishment_id ?? auth()->user()->establishment_id;

        return $query->where('establishment_id', $establishment_id)->select('id')->firstOrFail()->id;
    }

}
