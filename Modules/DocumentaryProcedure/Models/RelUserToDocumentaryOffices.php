<?php

namespace Modules\DocumentaryProcedure\Models;

use App\Models\Tenant\ModelTenant;
use Modules\User\Models\User;

class RelUserToDocumentaryOffices extends ModelTenant
{
	protected $table = 'rel_user_to_documentary_offices';

	protected $fillable = [
        'active',
        'user_id',
        'documentary_office_id',
    ];

    /**
     * @return bool
     */
    public function getActive() {
        return (bool) $this->active;
    }

    /**
     * @param mixed $active
     *
     * @return RelUserToDocumentaryOffices
     */
    public function setActive($active) {
        $this->active = (bool) $active;
        return $this;
    }
	/**
     * @return int
     */
    public function getUserId()
     {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     *
     * @return RelUserToDocumentaryOffices
     */
    public function setUserId($user_id)
    : RelUserToDocumentaryOffices {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getDocumentaryOfficeId()
     {
        return $this->documentary_office_id;
    }

    /**
     * @param int $documentary_office_id
     *
     * @return RelUserToDocumentaryOffices
     */
    public function setDocumentaryOfficeId($documentary_office_id)
    : RelUserToDocumentaryOffices {
        $this->documentary_office_id = $documentary_office_id;
        return $this;
    }

    public function getCollectionData() {
        $data = $this->toArray();
        $data['user'] = User::find($this->user_id);
        $data['documentary_office'] =DocumentaryOffice::find($this->documentary_office_id);
        return $data;
    }


    }
