<?php

    namespace Modules\Subscription\Http\Resources;

    use Illuminate\Http\Resources\Json\ResourceCollection;

    /**
     *
     * @mixin ResourceCollection
     */
    class UserRelSubscriptionPlansCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param \Illuminate\Http\Request $request
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