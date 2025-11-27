<?php

namespace Database\Seeders;

use App\Models\Reason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Reason::create([
            'title' => 'Ampliació',
            'slug' => 'expansion'
        ]);
        Reason::create([
            'title' => 'Baixa voluntària',
            'slug' => 'voluntary'
        ]);
        Reason::create([
            'title' => 'Canvi de nom',
            'slug' => 'change_name'
        ]);
        Reason::create([
            'title' => 'Canvi ubicació',
            'slug' => 'change_location'
        ]);
        Reason::create([
            'title' => 'Expedient incompliment normativa',
            'slug' => 'non-compliance_file'
        ]);
        Reason::create([
            'title' => 'Fi de la carencia',
            'slug' => 'end'
        ]);
        Reason::create([
            'title' => 'Jubilació',
            'slug' => 'retirement'
        ]);
        Reason::create([
            'title' => 'Mort',
            'slug' => 'death'
        ]);
        Reason::create([
            'title' => 'Reducció',
            'slug' => 'reduction'
        ]);
        Reason::create([
            'title' => 'Traspàs de parada',
            'slug' => 'transfer'
        ]);

    }
}
