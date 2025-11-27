<?php

namespace Database\Seeders;

use App\Models\Incidences;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncidenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $incidence =  [
            'stall_id' => 1,
            'contact_email_id' => 1,
            'title' => 'Title 1',
            'type' => 'incidencia para titular',
            'description' => 'Description 1',
            'date_incidence' => Carbon::now()->toDateString(),
            'user_id' => 1
        ];

        Incidences::create($incidence);

        $incidence =  [
            'stall_id' => 4,
            'contact_email_id' => 4,
            'title' => 'Title 4',
            'type' => 'incidencia para titular',
            'description' => 'Description 4',
            'date_incidence' => Carbon::now()->toDateString(),
            'user_id' => 1
        ];

        Incidences::create($incidence);

        $incidence =  [
            'stall_id' => 8,
            'contact_email_id' => 8,
            'title' => 'Title 8',
            'type' => 'incidencia para titular',
            'description' => 'Description 8',
            'date_incidence' => Carbon::now()->toDateString(),
            'user_id' => 1
        ];

        Incidences::create($incidence);
    }
}
