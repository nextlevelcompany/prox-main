<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TrackApiPeruServices extends ModelTenant
{
    public $timestamps = false;
    protected $perPage = 25;

    protected $fillable = [
        'service',
        'date_of_issue'
    ];

    protected $casts = [
        'service' => 'int'
    ];

    /**
     * Establece una consulta de un servicio
     * 1 => sunat/dni
     * 2 => validacion_multiple_cpe
     * 3 => CPE
     * 4 => tipo_de_cambio
     * 5 => printer_ticket
     *
     * @param null $dummy
     * @param int $service
     *
     * @return $this
     */
    public function setService($dummy = null, $service = 0)
    {
        $this->date_of_issue = Carbon::now();
        $this->service = $service;
        return $this;
    }
}
