<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 1;
        Market::get()->each(function ($item, $key) use ($i){
            $item->stalls()->create([
               'active' => true,
               'num' => $i,
               'length' => 10,
               'market_group_id' => $i,
               'classe_id' => 3 // Parada
            ]);
            $i++;

            $item->stalls()->create([
                'active' => true,
                'num' => $i,
                'length' => 15 + $i,
                'market_group_id' => $i,
                'classe_id' => 3 // Parada
            ]);
            $i++;

            $item->stalls()->create([
                'active' => true,
                'num' => $i,
                'length' => 20 + $i,
                'market_group_id' => $i,
                'classe_id' => 3 // Parada
            ]);
            $i++;
        });
    }
}
