<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Town;

class TownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Town::create([
            'id' => 1,
            'name' => 'Manacor'
        ]);
        Town::create([
            'id' => 2,
            'name' => 'Sillot'
        ]);
    }
}
