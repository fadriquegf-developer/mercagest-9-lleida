<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Person;

class LicenseNumberImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $person = Person::where('dni', $row['dni'])->first();
        if ($person) {
            \Log::info($person->id);
            $person->license_number = $row['num'];
            $person->save();
        }

        return $person;
    }
}
