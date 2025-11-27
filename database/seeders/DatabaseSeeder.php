<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TownSeeder::class,
            RatesSeeder::class,
            MarketSeeder::class,
            MarketGroupSeeder::class,
            PermissionSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
            SectorSeeder::class,
            AuthProdSeeder::class,
            ClasseSeeder::class,
            StallSeeder::class,
            ContactEmailSeeder::class,
            MarketDatesSeeder::class,
            //IncidenceSeeder::class,
            PersonSeeder::class,
            SettingSeeder::class,
            HistoricalSeeder::class,
            //ConcessionSeeder::class,
            //ChecklistTarragonaSeeder::class
            ReasonsSeeder::class
        ]);
    }
}
