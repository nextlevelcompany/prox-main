<?php

namespace Modules\Order\Models;

use App\Models\Tenant\ModelTenant;

class ConfigurationMiTiendaPe extends ModelTenant
{
    protected $table = 'configuration_mi_tienda_pe';

    protected $casts = [
        'establishment_id' => 'int',
        'series_order_note_id' => 'int',
        'series_document_ft_id' => 'int',
        'series_document_bt_id' => 'int',
        'user_id' => 'int',
        'payment_destination_id' => 'int',
        'autogenerate' => 'bool',
    ];

    protected $fillable = [
        'establishment_id',
        'series_order_note_id',
        'series_document_ft_id',
        'series_document_bt_id',
        'user_id',
        'autogenerate',
        'payment_destination_id',
        'currency_type_id'
    ];

    /**
     * @return bool
     */
    public function getAutogenerate(): bool
    {
        return (bool)$this->autogenerate;
    }

    /**
     * @param bool|null $autogenerate
     *
     * @return ConfigurationMiTiendaPe
     */
    public function setAutogenerate(?bool $autogenerate): ConfigurationMiTiendaPe
    {
        $this->autogenerate = (bool)$autogenerate;
        return $this;
    }

}

