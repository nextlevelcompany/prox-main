<?php

namespace Modules\FullSubscription\Http\Resources;

use Modules\Person\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionPersonCollection extends ResourceCollection
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
            /** @var Person $row */
            return $row->getCollectionData(true, false, true);
        });
    }
}
