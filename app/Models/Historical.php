<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Historical extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'historicals';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'stall_id', 'person_id', 'start_at', 'ends_at', 'end_vigencia', 'family_transfer', 'reason', 'explained_reason'];
    protected $appends = ['market_name'];
    protected $dates = [
        'start_at' => 'date:d-m-Y',
        'ends_at' => 'date:d-m-Y',
        'end_vigencia' => 'date:d-m-Y',
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

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeByMarketSelected($query)
    {
        if (Cache::has('market' . auth()->user()->id)) {
            return $query->whereHas('stall', function ($query) {
                $query->where('market_id', Cache::get('market' . auth()->user()->id));
            });
        } else {
            return $query->whereHas('stall', function ($query) {
                $query->whereIn('market_id', backpack_user()->my_markets->pluck('id')->toArray());
            });
        }
    }

    public function scopeActive($query)
    {
        return $query->whereNull('ends_at')->where(function ($query) {
            $query->whereDate('end_vigencia', '>', now())->orWhereNull('end_vigencia');
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

    public function getMarketNameAttribute()
    {
        return $this->stall->market->name ?? '';
    }
}
