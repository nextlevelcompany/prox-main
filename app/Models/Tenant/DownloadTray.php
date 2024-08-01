<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

class DownloadTray extends ModelTenant
{
    protected $table = 'download_tray';

    protected $fillable = [
        'user_id',
        'module',
        'format',
        'file_name',
        'status',
        'date_init',
        'date_end',
        'payload_request',
        'path',
        'type'
    ];

    protected $casts = [
        'user_id' => 'int'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
