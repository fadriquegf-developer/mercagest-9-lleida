<?php

namespace Database\Seeders;

use App\Models\ContactEmail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contact_emails = [
            [
                'name' => 'Roberto Faccini',
                'email' => 'rfservizifinanziari@gmail.com'
            ],
            [
                'name' => 'Christian Cicero',
                'email' => 'ccicero@cbmnational.org'
            ],
            [
                'name' => 'Gabriella Bocchi',
                'email' => 'gabriella.bocchi@cedacri.it'
            ],
            [
                'name' => 'Marilena Puppi',
                'email' => 'marilena.puppi@ogs.it'
            ],
            [
                'name' => 'Denis-Scozzin',
                'email' => 'denisscozzin@yahoo.it'
            ],
            [
                'name' => 'Alfonso Palma',
                'email' => 'info@alfonsopalma.it'
            ],
            [
                'name' => 'Francesca Belloni',
                'email' => 'francesca.belloni@camomilla.it'
            ],
            [
                'name' => 'Xinying Ji',
                'email' => 'xinyingji333@hotmail.com'
            ],
            [
                'name' => 'Matteo Senor',
                'email' => 'mortexvk2@yahoo.it'
            ]
        ];

        foreach ($contact_emails as $contact_email){
            ContactEmail::create($contact_email);
        }
    }
}
