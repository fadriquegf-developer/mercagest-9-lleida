<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Market;

class MarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Market::create([
            'name' => 'Mercat Plaça Major',
            'rate_id' => 1,
            'slug' => 'mercat-placa-major',
            'town_id' => 1,
            'days_of_week' => [1, 2, 3]
        ])->setTranslations('name', ['es' => 'Mercado plaza mayor', 'en' => 'Market Plaza Mayor'])
            ->setTranslations('slug', ['es' => 'mercado-plaza-mayor', 'en' => 'market-plaza-mayor'])
            ->save();

        Market::create([
            'name' => 'Mercat Nocturn Dijous',
            'rate_id' => 1,
            'slug' => 'mercat-nocturn-dijous',
            'town_id' => 2,
            'days_of_week' => [4, 5, 6]
        ])->setTranslations('name', ['es' => 'Mercado Nocturno Jueves', 'en' => 'Thursday Night Market'])
            ->setTranslations('slug', ['es' => 'mercado-nocturno-jueves', 'en' => 'thursday-night-market'])
            ->save();

        Market::create([
            'name' => 'Mercat Platja Dissabtes',
            'rate_id' => 1,
            'slug' => 'mercat-platja-dissabtes',
            'town_id' => 2,
            'days_of_week' => [6]
        ])->setTranslations('name', ['es' => 'Mercado Playa Sabados', 'en' => 'Saturday Beach Market'])
            ->setTranslations('slug', ['es' => 'mercado-playa-sabados', 'en' => 'saturday-beach-market'])
            ->save();
    }
}
