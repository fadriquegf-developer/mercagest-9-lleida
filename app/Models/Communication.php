<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Communication extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'communications';
    protected $fillable = [
        'market_id',
        'auth_prod_id',
        'sector_id',
        'user_id',
        'type',
        'title',
        'message',
    ];

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

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function auth_prod()
    {
        return $this->belongsTo(AuthProd::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stalls()
    {
        return $this->belongsToMany(Stall::class);
    }

    public function persons()
    {
        return $this->belongsToMany(Person::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeByMarketSelected($query)
    {
        if (Cache::has('market' . auth()->user()->id)) {
            return $query->where(function ($query) {
                $query->where('market_id', Cache::get('market' . auth()->user()->id))->orWhereNull('market_id');
            });
        } else {
            return $query->where(function ($query) {
                $query->whereIn('market_id', backpack_user()->my_markets->pluck('id')->toArray())->orWhereNull('market_id');
            });
        }
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
