<?php

namespace Modules\Catalog\Models;

class NoteCreditType extends ModelCatalog
{

    protected $table = "cat_note_credit_types";
    public $incrementing = false;

    public const PAYMENT_DATE_ADJUSTMENTS_CODE = '13';

}
