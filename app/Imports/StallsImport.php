<?php

namespace App\Imports;


use App\Models\Stall;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StallsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //Posicions a la data si te 7, primer numero es dia, segon i tercer mes, 4 darrers any, sino es que seran 8 i el dos primers sera el dia
        if(strlen($row['datalta']) == 7){
            $day = substr($row['datalta'], 0, 1);
            $month = substr($row['datalta'], 1, 2);
            $years = substr($row['datalta'], 3, 4);
        }else{
            $day = substr($row['datalta'], 0, 2);
            $month = substr($row['datalta'], 2, 2);
            $years = substr($row['datalta'], 4, 4);
        }
        
        $date = $years.'-'.$month.'-'.str_pad($day, 2, "0", STR_PAD_LEFT);
        
        $stall = Stall::where('num', $row['textub'])->whereHas('historicals', function ($query) use ($row, $date) {
            $query->where('dni', $row['nif'])->where('start_at', $date);
        })->first();

        if($stall){
            if($row['metlin'] == 0){
                $stall->length = $row['npuesto'];
            }else{
                $stall->length = $row['metlin'];
            }
            $stall->save();
        }
        else{
            \Log::info('Parada '.$row['textub'].' per dni '.$row['nif'].' no encontrada');
        }
    }
}
