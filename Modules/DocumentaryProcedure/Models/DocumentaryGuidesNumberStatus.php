<?php

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;

class DocumentaryGuidesNumberStatus extends ModelTenant
{
    public $timestamps = false;
    protected $table = 'documentary_guides_number_status';
    protected $fillable = [
        'name',
        'color',
    ];

    public function getCollectionData()
    {
        $data = $this->toArray();
        return $data;
    }
}
