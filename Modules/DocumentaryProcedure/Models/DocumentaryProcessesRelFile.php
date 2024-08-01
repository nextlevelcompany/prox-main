<?php

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;

class DocumentaryProcessesRelFile extends ModelTenant
{
    protected $table = 'documentary_processes_rel_file';
    protected $perPage = 25;

    protected $fillable = [
        'doc_processes_id',
        'doc_file_id',
        'doc_office_id',
        'stages',
        'complete'
    ];

    protected $casts = [
        'doc_processes_id' => 'int',
        'doc_file_id' => 'int',
        'doc_office_id' => 'int',
        'complete' => 'int'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_file()
    {
        return $this->belongsTo(DocumentaryFile::class, 'doc_file_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_office()
    {
        return $this->belongsTo(DocumentaryOffice::class, 'doc_office_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_processes()
    {
        return $this->belongsTo(DocumentaryProcess::class, 'doc_processes_id');
    }
}
