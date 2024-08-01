<?php

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;

class DocumentaryFilesRequirement extends ModelTenant
{
    protected $perPage = 25;

    protected $fillable = [
        'name',
        'file',
    ];

    protected $casts = [
        'file' => 'bool',
    ];

    /**
     * Relaciona los tramites con los requerimientos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documentary_processes_rel_reqs_where_doc_files_requirement()
    {
        return $this->hasMany(DocumentaryProcessesRelReq::class, 'doc_files_requirements_id');
    }

    /**
     * @param string|null $name
     *
     * @return DocumentaryFilesRequirement
     */
    public function setName(?string $name): DocumentaryFilesRequirement
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param bool $file
     *
     * @return DocumentaryFilesRequirement
     */
    public function setFile(bool $file = false): DocumentaryFilesRequirement
    {
        $this->file = (bool)$file;
        return $this;
    }

    /**
     * @return array
     */
    public function getCollectionData()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->getName(),
            'file' => $this->getFile(),
        ];
        return $data;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function getFile(): bool
    {
        return (bool)$this->file;
    }


}
