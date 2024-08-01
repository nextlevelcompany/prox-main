<?php

namespace Modules\Purchase\Http\Resources;

use Modules\Company\Models\Configuration;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

/**
 * Class PurchaseItemCollection
 *
 * @package App\Http\Resources\Tenant
 */
class PurchaseItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Collection
     */
    public function toArray($request)
    {
        $configuration = Configuration::first();
        return $this->collection->transform(function ($row, $key) use ($configuration) {
            return $row->getCollectionData($configuration);
        });
    }

}
