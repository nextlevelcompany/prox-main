<?php

namespace Modules\Company\Models;

use App\Models\Tenant\ModelTenant;

class Skin extends ModelTenant
{
    protected $fillable = [
        'name',
        'filename',
        'status'
    ];

    /**
     * @return array
     */
    public function getCollectionData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'filename' => $this->filename,
            'status' => $this->status,
        ];
    }
}
