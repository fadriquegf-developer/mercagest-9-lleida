<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rate;

class RatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rate::create([
            'id' => 1,
            'name' => 'Tarifa Nocturna',
            'price' => 45
        ]);
        Rate::create([
            'id' => 2,
            'name' => 'Tarifa Fin de Semana',
            'price' => 60
        ]);
        Rate::create([
            'id' => 3,
            'name' => 'Tarifa Mediodía',
            'price' => 30
        ]);
    }
}
