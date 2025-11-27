<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Concession extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'concessions';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'auth_prod_id', 'stall_id', 'start_at', 'end_at', 'pdf'];
    protected $dates = [
        'start_at',
        'end_at'
    ];
    protected $appends = ['auth_prod_name','sector_name'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($obj) {
            \Storage::disk('public')->delete($obj->pdf);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /* public function auth_prod()
    {
        return $this->belongsTo(AuthProd::class);
    }

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    } */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getPdfAttribute($value)
    {
        if ($value) return $this->pdf = '/storage/' . $value;
    }

    public function getAuthProdNameAttribute()
    {
        return $this->auth_prod->name;
    }

    public function getSectorNameNameAttribute()
    {
        return $this->auth_prod->sector->name;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPdfAttribute($value)
    {
        $attribute_name = "pdf";
        $disk = "local";
        $destination_path = app('tenant')->name."/concession";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
