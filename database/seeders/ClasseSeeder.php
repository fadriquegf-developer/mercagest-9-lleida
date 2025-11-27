<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Classe::create([
            'name' => 'Quiosc'
        ])->save();

        Classe::create([
            'name' => 'Modul'
        ])->save();

        Classe::create([
            'name' => 'Parada'
        ])->save();

        Classe::create([
            'name' => 'Quadre'
        ])->save();

        Classe::create([
            'name' => 'Altres Despeses Modul'
        ])->save();

        Classe::create([
            'name' => 'Local'
        ])->save();

        Classe::create([
            'name' => 'Naus MC'
        ])->save();
        
        Classe::create([
            'name' => 'Terrasses'
        ])->save();
    }
}
