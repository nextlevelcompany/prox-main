<?php

namespace Modules\Person\Observers;


use Modules\Person\Models\Person;

class PersonObserver
{
    public function saving(Person $person)
    {
        $text = [];
        $text[] = $person->number;
        $text[] = $person->name;
        if (property_exists($person, 'text_filter')) {
            $person->text_filter = join('|', $text);
        }
    }
}
