<?php

namespace Modules\Report\Http\Resources;

use App\CoreFacturalo\Helpers\Functions\FunctionsHelper;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentItem;
use Modules\SaleNote\Models\SaleNote;
use Modules\SaleNote\Models\SaleNoteItem;
use Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Modules\Report\Helpers\UserCommissionHelper;

class ReportCommissionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function toArray($request)
    {
        /**
         * @var Collection $data
         */

        $data = $this->collection->transform(function ($row, $key) use ($request) {

            return UserCommissionHelper::getDataForReportCommission($row, $request);

            // return $data_commission;
            // dd($data_commission);

        });

        return $data;
    }

}
