<?php

namespace Modules\Catalog\Models;

use Modules\Company\Models\Company;
use Modules\Dispatch\Models\Dispatcher;
use Modules\Dispatch\Models\Driver;
use Modules\Person\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\BusinessTurn\Models\DocumentHotel;
use Modules\BusinessTurn\Models\DocumentTransport;

class IdentityDocumentType extends ModelCatalog
{
    public $incrementing = false;
    protected $table = "cat_identity_document_types";

    protected $fillable = [
        'id',
        'active',
        'description'
    ];

    /**
     * @return HasMany
     */
    public function companies_where_identity_document_type()
    {
        return $this->hasMany(Company::class, 'identity_document_type_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function dispatchers_where_identity_document_type()
    {
        return $this->hasMany(Dispatcher::class, 'identity_document_type_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function document_hotels_where_identity_document_type()
    {
        return $this->hasMany(DocumentHotel::class, 'identity_document_type_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function document_transports_where_identity_document_type()
    {
        return $this->hasMany(DocumentTransport::class, 'identity_document_type_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function drivers_where_identity_document_type()
    {
        return $this->hasMany(Driver::class, 'identity_document_type_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function people_where_identity_document_type()
    {
        return $this->hasMany(Person::class, 'identity_document_type_id', 'id');
    }


    /**
     *
     * Filtrar tipos de documentos para usuarios
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilterDataForPersons($query)
    {
        return $query->whereIn('id', ['0', '1', '4', '7']);
    }

}
