<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Absence extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    const TYPE_ABSENT = 'no-justificada';
    const TYPE_PRESENT = 'justificada';

    protected $table = 'absences';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'person_id', 'stall_id', 'type', 'cause', 'document', 'start', 'end'];
    protected $appends = ['market'];
    protected $dates = ['start', 'end'];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getDocumentUrl()
    {
        if ($this->document) return url(config('backpack.base.route_prefix', 'admin') . '/storage/' . $this->document);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function stall()
    {
        return $this->belongsTo(Stall::class);
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

    /**
     * Used to prvent multiple abcenses in the same time range
     */
    public function scopeByBusy($query, $start_date, $end_date)
    {
        return $query->where(function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start', [$start_date, $end_date])
                ->orWhereBetween('end', [$start_date, $end_date])
                ->orWhereRaw('? BETWEEN start and end', [$start_date])
                ->orWhereRaw('? BETWEEN start and end', [$end_date]);
        });
    }

    public function scopeFilterByDate($query, $date)
    {
        return $query->whereDate('start', '<=', $date)->whereDate('end', '>=', $date);
    }

    public function scopeFilterByStall($query, $stall_id)
    {
        return $query->whereHas('person.historicals', function ($query) use ($stall_id) {
            $query->where('stalls.id', $stall_id);
        });
    }

    public function scopeAbsent($query)
    {
        return $query->where('type', 'no-justificada');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getMarketAttribute()
    {
        $calendar = $this->calendar;
        if ($calendar) return $calendar->market->name;
    }

    public function getPersonStallNameAttribute()
    {
        $person = $this->person ?  $this->person->name : '';
        $stall = $this->stall ?  $this->stall->num_market : '';

        return "$stall - $person";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setDocumentAttribute($value)
    {
        if (is_uploaded_file($value)) {
            $attribute_name = "document";
            $disk = "local";
            $destination_path =  app('tenant')->name . "/absence";
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        } else {
            //Si no es uploaded file, viene de migracion
            $this->attributes['document'] = $value;
        }
    }
}
