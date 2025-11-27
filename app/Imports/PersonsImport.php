<?php

namespace App\Imports;

use App\Models\Person;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PersonsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row);
        //dd(date_create_from_format('Ymd' ,$row['data_domiciliacio']));
        $person = Person::where('dni', $row['nif_paradista'])->first();
        if($person){
            \Log::info($person->id);
            $person->name_titular = $row['nom_titular'];
            $person->nif_titular = $row['nif_titular'];
            $person->iban = $row['compte'];
            $person->ref_domiciliacion = $row['referencia_domiciliacio'];
            $person->date_domiciliacion = date_create_from_format('Ymd' ,$row['data_domiciliacio']);
            $person->save();
        }
    }
}
