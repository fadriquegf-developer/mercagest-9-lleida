<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Incidences extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'market_id',
        'person_id',
        'stall_id',
        'title',
        'type',
        'add_absence',
        'description',
        'images',
        'date_incidence',
        'status',
        'date_solved',
        'send',
        'contact_email_id',
        'can_mount_stall'
    ];

    protected $dates = [
        'date_incidence',
        'date_solved'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function contactEmail($crud = false)
    {
        return '<a href="' . route('contact-email.index') . '" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-list"></i> ' . __('backpack.incidences.contact_email') . '</span></a>';
    }

    public function getImageUrl($image)
    {
        if ($image) return url(config('backpack.base.route_prefix', 'admin') . '/storage/' . $image);
    }

    public function transType()
    {
        $types = __('backpack.incidences.types');

        return $types[$this->type] ?? '';
    }

    public function generatePdf()
    {
        $data = [];
        $data['entry'] = $this;

        $pdf = PDF::loadView('tenant.'.app()->tenant->name.'.incidence.pdf', $data);

        return $pdf;
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

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }

    public function contact_email()
    {
        return $this->belongsTo(ContactEmail::class);
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeByMarketSelected($query)
    {
        if (Cache::has('market' . auth()->user()->id)) {
            $id = Cache::get('market' . auth()->user()->id);
            return $query = $query->whereHas('stall.market', function ($query) use ($id) {
                $query->where('id', $id);
            })->orWhere('market_id', $id);
        } else {
            $ids = backpack_user()->my_markets->pluck('id')->toArray();
            return $query = $query->whereHas('stall.market', function ($query) use ($ids) {
                $query->whereIn('id', $ids);
            })->orWhereIn('market_id', $ids);
        }
    }

    public function scopeFilterByDate($query, $date)
    {
        return $query->whereDate('date_incidence', $date);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
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
    public function setImagesAttribute($value)
    {
        $attribute_name = "images";
        $disk = "local";
        $destination_path = app('tenant')->name."/incidence";
        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
