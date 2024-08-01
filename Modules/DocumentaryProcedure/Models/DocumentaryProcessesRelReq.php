<?php

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;

class DocumentaryProcessesRelReq extends ModelTenant
{
    protected $table = 'documentary_processes_rel_req';
    protected $perPage = 25;

    protected $fillable = [
        'doc_processes_id',
        'doc_files_requirements_id',
        'active',
    ];

    protected $casts = [
        'doc_processes_id' => 'int',
        'doc_files_requirements_id' => 'int',
        'active' => 'bool',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_files_requirements()
    {
        return $this->belongsTo(DocumentaryFilesRequirement::class, 'doc_files_requirements_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doc_processes()
    {
        return $this->belongsTo(DocumentaryProcess::class, 'doc_processes_id');
    }

    /**
     * @return int|null
     */
    public function getDocProcessesId(): ?int
    {
        return $this->doc_processes_id;
    }

    /**
     * @param int|null $doc_processes_id
     *
     * @return DocumentaryProcessesRelReq
     */
    public function setDocProcessesId(?int $doc_processes_id): DocumentaryProcessesRelReq
    {
        $this->doc_processes_id = $doc_processes_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDocFilesRequirementsId(): ?int
    {
        return $this->doc_files_requirements_id;
    }

    /**
     * @param int|null $doc_files_requirements_id
     *
     * @return DocumentaryProcessesRelReq
     */
    public function setDocFilesRequirementsId(?int $doc_files_requirements_id): DocumentaryProcessesRelReq
    {
        $this->doc_files_requirements_id = $doc_files_requirements_id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)$this->active;
    }

    /**
     * @param bool $active
     *
     * @return DocumentaryProcessesRelReq
     */
    public function setActive(bool $active): DocumentaryProcessesRelReq
    {
        $this->active = (bool)$active;
        return $this;
    }


    /**
     * @return array
     */
    public function getCollectionData()
    {
        $data = [];
        $data['active'] = $this->isActive();
        $data['requirement_name'] = '';
        $data['requirement_file'] = '';
        $data['requirement_id'] = '';
        $data['requirement_id_check'] = '';
        $data['process_id'] = '';
        $data['process_name'] = '';
        $data['process_active'] = '';
        $data['process_price'] = '';


        /** @var DocumentaryFilesRequirement $req */
        $req = $this->doc_files_requirements()->first();
        if ($req != null) {
            $data['requirement_name'] = $req->getName();
            $data['requirement_file'] = $req->getFile();
            $data['requirement_id'] = $req->id;
            $data['requirement_id_check'] = (string)$req->id;
            $data['check'] = false;
        }
        /** @var DocumentaryProcess $proc */
        $proc = $this->doc_processes()->first();
        if ($proc != null) {
            $data['process_id'] = $proc->id;
            $data['process_name'] = $proc->getName();
            $data['process_active'] = $proc->isActive();
            $data['process_price'] = $proc->getPrice();
            // $data['process'] = $proc->getCollectionData();
        }
        return $data;
    }
}
