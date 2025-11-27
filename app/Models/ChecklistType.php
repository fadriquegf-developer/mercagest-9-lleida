<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ChecklistType extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    const TYPE_STALL = 'stall';
    const TYPE_MARKET = 'market';

    protected $fillable = ['name', 'allowed_sector', 'forbidden_sector'];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function checklist_groups()
    {
        return $this->hasMany(ChecklistGroup::class);
    }

    public function markets()
    {
        return $this->belongsToMany(Market::class, 'checklist_allowed_market');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeFilterByMarket($query)
    {
        return $query->whereHas('markets', function ($query) {
            // allowed ids
            $query->whereIn('id', backpack_user()->my_markets->pluck('id')->toArray());
            // current market selected
            $current_market_id = \Cache::get('market' . auth()->user()->id);
            if ($current_market_id) {
                $query->where('id', $current_market_id);
            }
        });
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
