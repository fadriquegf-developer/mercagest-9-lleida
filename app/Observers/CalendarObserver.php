<?php

namespace App\Observers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CalendarObserver
{
    /**
     * Handle the Calendar "creating" event.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return void
     */
    public function creating(Calendar $calendar)
    {
        // TODO: get current market
        if (!$calendar->market_id)
            $calendar->market_id = Cache::get('market'. auth()->user()->id) ?? 1;
    }
}
