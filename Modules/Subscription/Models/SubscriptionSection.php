<?php

namespace Modules\Subscription\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionSection extends ModelTenant
{
    protected $table = 'subscription_section';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     *
     * Obtener datos para el listado y edicion
     *
     * @return array
     */
    public function getRowResource()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

}