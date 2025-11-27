<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use App\Observers\CalendarObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Calendar extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'calendar';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'market_id',
        'date'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    /**
     * Register any events for your application.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(CalendarObserver::class);
    }

    public function calendarView(){
        return '<a href="#" class="btn btn-primary" role="button" id="calendar_view"><span class="ladda-label"><i class="la la-calendar"></i> ' . __('backpack.calendar.calendar_view') . '</span></a>';
    }
    
    public function tableView(){
        return '<a href="#" class="btn btn-primary" role="button" id="table_view"><span class="ladda-label"><i class="la la-table"></i> ' . __('backpack.calendar.table_view') . '</span></a>';
    }

    public function marketDay()
    {
        $market_id = Str::between(request()->getRequestUri(), '/admin/market/', '/calendar');
        return '<a href="' . url('/admin/market/' . $market_id . '/calendar/create-range') . '" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus"></i> ' . __('backpack.calendar.market_day') . '</span></a>';
    }

    public function dayreport()
    {
        return '<a href="' . url('/admin/day-report/'.$this->date) . '" class="btn btn-sm btn-link" data-style="zoom-in"><span class="ladda-label"><i class="la la-bug"></i> ' .  __('backpack.calendar.day_report') . '</span></a>'; 
    }

    public function addAbsence()
    {
        return '<a href="' . url('/admin/absence/create?date='.$this->date) . '" class="btn btn-sm btn-link" data-style="zoom-in"><span class="ladda-label"><i class="la la-user-alt-slash"></i> ' .  __('backpack.calendar.add_abcense') . '</span></a>'; 
    }

    public function addIncedence()
    {
        return '<a href="' . url('/admin/incidences/create?date='.$this->date) . '" class="btn btn-sm btn-link" data-style="zoom-in"><span class="ladda-label"><i class="la la-exclamation-circle"></i> ' .  __('backpack.calendar.add_incidence') . '</span></a>'; 
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeFilterByMarket($query, $id = null)
    {
        if (!$id) $id = Cache::get('market' . auth()->user()->id);
        return $query->where('market_id', $id);
    }

    public function scopeFilterByUserMarket($query)
    {
        return $query->whereIn('market_id', backpack_user()->my_markets->pluck('id')->toArray());
    }

    public function scopeByMarketSelected($query)
    {
        if (Cache::has('market' . auth()->user()->id)) {
            return $query->where('market_id', Cache::get('market' . auth()->user()->id));
        } else {
            return $query->whereIn('market_id', backpack_user()->my_markets->pluck('id')->toArray());
        }
    }

    public function scopeFilterByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeFilterByDateRange($query, $from, $to)
    {
        return $query->where('date', '>=', $from)
            ->where('date', '<', $to);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
