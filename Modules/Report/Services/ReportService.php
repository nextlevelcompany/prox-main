<?php

namespace Modules\Report\Services;

use Modules\Person\Models\Person;
use Modules\User\Models\User;

class ReportService
{

    public function getPersonName($person_id)
    {
        $person = Person::find($person_id);

        if($person){
            return $person->name;
        }

        return '';
    }

    public function getUserName($user_id)
    {
        $user = User::find($user_id);

        if($user){
            return $user->name;
        }

        return '';
    }


}
