<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\CalendarCrudController;
use App\Models\Market;
use Carbon\CarbonPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $period = CarbonPeriod::between("01/01/2022", "04/30/2022");
        $markets = Market::get();
        foreach ($markets as $market){
            $calendar = new CalendarCrudController();
            $calendar->createMarketDatesByMarket($market, $period);
        }
    }
}
