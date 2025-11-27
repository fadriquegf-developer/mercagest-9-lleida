<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    const DISCOUNT = 'discount';
    const PERCENTAGE = 'percentage';
    const DAYS = 'days';

    protected $table = 'bonuses';
    protected $fillable = [
        'stall_id',
        'type',
        'amount',
        'start_at',
        'ends_at',
        'reason',
        'title'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getPriceChanged($price, $market_days = null)
    {
        if ($this->type == Bonus::DISCOUNT) {
            if ($this->amount < $price) {
                $price = $this->amount;
            }
        }

        if ($this->type == Bonus::PERCENTAGE) {
            $price = round($price * $this->amount / 100, 1);
        }

        if ($this->type == Bonus::DAYS) {
            $price = round(($price / $market_days) * $this->amount * 0.75, 1);
        }

        return $price;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeFilterByDateRange($query, $from, $to)
    {
        return $query->whereDate('start_at', '<=', $to)
            ->whereDate('ends_at', '>=', $from);
    }

    public function scopeFilterByDate($query, $date)
    {
        return $query->whereDate('start_at', '<=', $date->date)
            ->whereDate('ends_at', '>=', $date->date);
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
