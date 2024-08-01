<?php

namespace App\Services;

use Modules\Catalog\Models\DetractionType;
use Modules\Catalog\Models\PaymentMethodType;

class DetractionTypeService
{
    public function getDetractionTypeDescription($value)
    {
        $row = DetractionType::where('id', $value)->first();
        return $row ? $row->description:'Registro no encontrado';
    }


    public function getPaymentMethodTypeDescription($value)
    {
        $row = PaymentMethodType::where('id', $value)->first();
        return $row ? $row->description:'Registro no encontrado';
    }
}
