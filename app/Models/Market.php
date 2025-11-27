<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\Sluggable;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\SluggableScopeHelpers;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Support\Facades\Cache;

class Market extends Model
{
    use CrudTrait;
    use Sluggable, SluggableScopeHelpers;
    use HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'markets';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'status',
        'name',
        'town_id',
        'rate_id',
        'days_of_week',
        'market_group_id'
    ];
    protected $translatable = [
        'name',
        'slug'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getDaysOfWeek()
    {
        $market_days = [];
        foreach ($this->days_of_week as $day) {
            $market_days[] = __('backpack.markets.option_days_of_week')[$day];
        }
        return implode(', ', $market_days);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    public function rates()
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    public function stalls()
    {
        return $this->hasMany(Stall::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function checklists()
    {
        return $this->morphMany(Checklist::class, 'origin');
    }

    public function markets()
    {
        return $this->belongsToMany(ChecklistType::class, 'checklist_allowed_market');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeSelected($query)
    {
        return $query->where('id', Cache::get('market' . auth()->user()->id));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getDaysOfWeekAttribute($val)
    {
        return json_decode($val);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setDaysOfWeekAttribute($val)
    {
        $this->attributes['days_of_week'] = json_encode($val);
    }
}
