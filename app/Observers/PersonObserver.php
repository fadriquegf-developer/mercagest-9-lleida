<?php

namespace App\Observers;

use App\Models\Person;

class PersonObserver
{
    public function deleting(Person $person)
    {
        if ($person->image) {
            \Storage::disk('local')->delete($person->image);
        }

        if ($person->pdf1) {
            \Storage::disk('local')->delete($person->pdf1);
        }

        if ($person->pdf2) {
            \Storage::disk('local')->delete($person->pdf2);
        }

        if ($person->substitute1_dni_img) {
            \Storage::disk('local')->delete($person->substitute1_dni_img);
        }

        if ($person->substitute1_img) {
            \Storage::disk('local')->delete($person->substitute1_img);
        }

        if ($person->substitute2_dni_img) {
            \Storage::disk('local')->delete($person->substitute2_dni_img);
        }

        if ($person->substitute2_img) {
            \Storage::disk('local')->delete($person->substitute2_img);
        }

        $person->substitutes()->detach();
    }
}
