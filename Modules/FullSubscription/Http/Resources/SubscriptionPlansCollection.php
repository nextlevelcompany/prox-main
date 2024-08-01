<?php

namespace Modules\FullSubscription\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\FullSubscription\Models\Tenant\SubscriptionPlan;

class SubscriptionPlansCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {
            return $row->getCollectionData();
        });
    }
}
