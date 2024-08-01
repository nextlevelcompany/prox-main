<?php

namespace Modules\Person\Models;

use App\Models\Tenant\ModelTenant;

class PersonType extends ModelTenant
{
    protected $fillable = [
        'description',

    ];

    /**
     * @return string
     */
    public function getDescription()
    : string {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return PersonType
     */
    public function setDescription(string $description)
    : PersonType {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function person(){
        return $this->hasMany(Person::class);
    }

}
