<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Concession;

class ConcessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* for($id = 1;$id <= 9 ;$id++){
            Concession::create([
                'id' => $id,
                'auth_prod_id' => $id, 
                'stall_id' => $id, 
                'start_at' => strtotime('2022-01-01'),
                'end_at' => strtotime('2030-01-01')
            ])->save();
        } */
    }
}
