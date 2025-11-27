<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Historical;

class HistoricalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($id = 1;$id <= 9 ;$id++){
            Historical::create([
                'id' => $id,
                'stall_id' => $id, 
                'person_id' => $id, 
                'start_at' => strtotime('2022-01-01'), 
                'family_transfer' =>'family-transfer'
            ])->save();
        }
    }
}
