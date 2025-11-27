<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'status',
        'person_id',
        'market_group_id',
        'type',
        'month',
        'trimestral',
        'years',
        'total',
        'special_edition'
    ];
    protected $appends = ['details', 'formated_month'];

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

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function market_group()
    {
        return $this->belongsTo(MarketGroup::class)->active();
    }

    public function concepts()
    {
        return $this->hasMany(InvoiceConcept::class)->orderBy('concept', 'ASC');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeFilterByDateRange($query, $request)
    {
        if (isset($request['start_date'])) {
            $query = $query->whereDate('month', '>=', $request['start_date']);
        }
        if (isset($request['end_date'])) {
            $query = $query->whereDate('month', '<=', $request['end_date']);
        }
        if (isset($request['years'])) {
            $query = $query->where(DB::raw('YEAR(month)'), '=', $request['years']);
        }
        return $query;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getConceptDetailsAttribute()
    {
        $table = [];
        foreach ($this->concepts as $concept) {
            switch ($concept->concept) {
                case 'stall':
                    $table_concept = [
                        'concept' => $concept->stall->market->name . ' - ' . $concept->stall->classe->name . ' - ' . $concept->stall->num,
                        'meters' => $concept->concept_type == 'meters' ? $concept->length . '㎡' : '',
                        'days' => $concept->type_rate == 'daily' ? $concept->qty_days : '',
                        'price' => $concept->price . '€',
                        'subtotal' => $concept->subtotal . '€',
                    ];
                    break;
                case 'expenses':
                    $table_concept = [
                        'concept' => 'Despeses Mant. ' . $concept->stall->market->name . ' - ' . $concept->stall->classe->name,
                        'meters' => $concept->concept_type == 'meters' ? $concept->length . '㎡' : '',
                        'days' => $concept->type_rate == 'daily' ? $concept->qty_days : '',
                        'price' => $concept->price . '€',
                        'subtotal' => $concept->subtotal . '€',
                    ];
                    break;
                case 'bonuses':
                    $table_concept = [
                        'concept' => isset($concept->title) ? $concept->title : 'Bonus',
                        'meters' => '',
                        'days' => '',
                        'price' => '',
                        'subtotal' => '-' . $concept->subtotal . '€',
                    ];
                    break;
            }
            array_push($table, $table_concept);
        }
        return $table;
    }

    public function getFormatedMonthAttribute()
    {
        return Carbon::parse($this->month)->diffForHumans() . ' (' . Carbon::parse($this->month)->toDateString() . ')';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
