<?php

namespace Modules\Order\Models;

use App\Models\Tenant\ModelTenant;

class StatusOrder extends ModelTenant
{
  public function order()
  {
      return $this->hasMany(Order::class);
  }
}
