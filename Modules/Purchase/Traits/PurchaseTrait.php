<?php

namespace Modules\Purchase\Traits;

use Modules\Cash\Models\Cash;
use Modules\Purchase\Models\Purchase;

trait PurchaseTrait
{
    public function createCashDocument()
    {
        Purchase::created(function ($purchase) {
            $cash = Cash::whereActive()->first();
            if ($cash) {
                $cash->cash_documents()->create(['purchase_id' => $purchase->id]);
            }
        });
    }
}
