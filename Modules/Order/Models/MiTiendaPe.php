<?php

namespace Modules\Order\Models;

use App\Models\Tenant\ModelTenant;
use Carbon\Carbon;

/**
 * Class MiTiendaPe
 *
 * @property int $id
 * @property string|null $order_number
 * @property string|null $transaction_code
 * @property int|null $order_note_id
 * @property int|null $document_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @package Modules\Order\Models
 */
class MiTiendaPe extends ModelTenant
{
    protected $table = 'mi_tienda_pe';

    protected $fillable = [
        'order_number',
        'transaction_code',
        'document_id',
        'order_note_id',
    ];

    protected $casts = [
        'order_note_id' => 'int',
        'document_id' => 'int',
    ];
}
