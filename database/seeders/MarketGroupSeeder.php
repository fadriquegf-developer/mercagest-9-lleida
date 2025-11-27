<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MarketGroup;

class MarketGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MarketGroup::create([
            'id' => 1,
            'type' => 'A',
            'gtt_type' => 'MB',
            'title' => 'Mercats Ambulants',
            'payment' => 'mensual'
        ])->save();
        MarketGroup::create([
            'id' => 2,
            'type' => 'E',
            'gtt_type' => 'MC',
            'title' => 'Eventuals',
            'payment' => 'trimestral'
        ])->save();
        MarketGroup::create([
            'id' => 3,
            'type' => 'B',
            'gtt_type' => 'MB',
            'title' => 'Ambulants Bordeta',
            'payment' => 'mensual'
        ])->save();
        MarketGroup::create([
            'id' => 4,
            'type' => 'C',
            'gtt_type' => 'MC',
            'title' => 'Mercats Fruita i Verdura',
            'payment' => 'trimestral'
        ])->save();
        MarketGroup::create([
            'id' => 5,
            'type' => 'M',
            'gtt_type' => 'MM',
            'title' => 'Mercats Municipals',
            'payment' => 'trimestral'
        ])->save();
        MarketGroup::create([
            'id' => 6,
            'type' => 'F',
            'gtt_type' => 'MB',
            'title' => 'Mercat Fruita i Verdura Ambulant',
            'payment' => 'trimestral'
        ])->save();
        MarketGroup::create([
            'id' => 7,
            'type' => 'MC',
            'gtt_type' => 'MA',
            'title' => 'Mercats Municipals - Central Nou',
            'payment' => 'mensual'
        ])->save();
        MarketGroup::create([
            'id' => 8,
            'type' => 'R',
            'gtt_type' => 'RB',
            'title' => 'Ambulants Rambla',
            'payment' => 'mensual'
        ])->save();
        MarketGroup::create([
            'id' => 9,
            'type' => 'H',
            'gtt_type' => 'HO',
            'title' => "Mercat de L'Hort a taula",
            'payment' => 'mensual'
        ])->save();
    }
}
