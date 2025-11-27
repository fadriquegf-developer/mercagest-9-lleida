<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Person::factory()->count(2)->create();
        Person::factory()->count(3)
            ->has(Person::factory()->count(2), 'substitutes')
            ->create();
        Person::factory()->count(3)
            ->has(Person::factory()->count(1), 'substitutes')
            ->create();
        Person::factory()->count(2)
            ->has(Person::factory()->count(3), 'substitutes')
            ->create();

    }
}
