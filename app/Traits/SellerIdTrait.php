<?php

namespace App\Traits;

trait SellerIdTrait
{
    public static function adjustSellerIdField(&$model): void
    {
        if (empty($model->seller_id)) {
            $model->seller_id = $model->user_id;
        }
    }
}
