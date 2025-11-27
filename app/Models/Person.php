<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Person extends Model
{
    use CrudTrait, HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'persons';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'dni',
        'name',
        'address',
        'email',
        'phone',
        'phone_2',
        'phone_3',
        'name_titular',
        'nif_titular',
        'iban',
        'date_domiciliacion',
        'ref_domiciliacion',
        'image',
        'pdf1',
        'pdf2',
        'city',
        'zip',
        'region',
        'province',
        'type_address',
        'number_address',
        'extra_address',
        'comment',
        'unsubscribe_date',
        'license_number',

        'substitute1_name',
        'substitute1_dni',
        'substitute1_dni_img',
        'substitute1_img',
        'substitute2_name',
        'substitute2_dni',
        'substitute2_dni_img',
        'substitute2_img',

        'legal_doc3'
    ];
    protected $appends = ['dni_code', 'ref_code'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function accreditation()
    {
        return '<a href="/admin/person/accreditation/' . $this->id . '" class="btn btn-sm btn-link" target="_blank"><i class="la la-download"></i> ' . __('backpack.stalls.accreditation') . '</a>';
    }

    public function btnLegalDoc1()
    {
        $target = $this->legal_doc_signature_date1 ? 'target="_blank"' : '';
        return '<a href="' . backpack_url("person/{$this->id}/legal-doc/1") . '" class="btn btn-sm btn-link" ' . $target . '><i class="la la-file-signature"></i> ' . __('backpack.persons.legal_doc1') . '</a>';
    }

    public function btnLegalDoc2()
    {
        $target = $this->legal_doc2_signature_date ? 'target="_blank"' : '';
        return '<a href="' . backpack_url("person/{$this->id}/legal-doc/2") . '" class="btn btn-sm btn-link" ' . $target . '><i class="la la-file-signature"></i> ' . __('backpack.persons.legal_doc2') . '</a>';
    }

    public function expedientesButton()
    {
        return '<a href="/admin/expediente?person_id=' . $this->id . '" class="btn btn-sm btn-link"><i class="la la-file-contract"></i> ' . __('backpack.stalls.expedientes') . '</a>';
    }

    public function addStall()
    {
        return '<a href="' . backpack_url('historical/create') . '?person_id=' . $this->id . '" class="btn btn-sm btn-link"><i class="la la-warehouse"></i> ' . __('backpack.stalls.add') . '</a>';
    }

    public function btnToggleUnsubscribe()
    {
        $textBtn = $this->unsubscribe_date ? __('backpack.persons.restore') :  __('backpack.persons.unsubscribe');
        $iconBtn = $this->unsubscribe_date ?  'la-folder-plus' : 'la-folder-minus';

        return '<a href="' . backpack_url("person/{$this->id}/toggle-subscribe") . '" class="btn btn-sm btn-link"><i class="la ' . $iconBtn . '"></i> ' . $textBtn . '</a>';
    }

    private function getUrl($value)
    {
        if ($value) return url(config('backpack.base.route_prefix', 'admin') . '/storage/' . $value);
    }

    public function getSubstitutesName()
    {
        $names = collect();
        if ($this->substitute1_name) {
            $names->push($this->substitute1_name);
        }
        if ($this->substitute2_name) {
            $names->push($this->substitute2_name);
        }

        return $names->implode(', ');
    }

    public function getFolderDocs()
    {
        return 'lleida/person/docs/';
    }

    public function getUserName($property){
        $id = $this->{$property};

        if($id){
            $user = User::find($id);
            return $user->name ?? '-';
        }

        return '-';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function owner()
    {
        return $this->belongsTo(Person::class);
    }

    public function substitutes()
    {
        return $this->belongsToMany(Person::class, 'substitute_person', 'person_id', 'substitute_id');
    }

    public function historicals()
    {
        return $this->belongsToMany(Stall::class, 'historicals')
            ->withPivot(['start_at', 'ends_at', 'end_vigencia', 'reason', 'family_transfer', 'explained_reason']);
    }

    public function incidences()
    {
        return $this->hasMany(Incidences::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function expedientes()
    {
        return $this->hasMany(Expediente::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function docs()
    {
        return $this->hasMany(PersonDoc::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeOrderByHistorical($query)
    {
        return $query->orderByDesc(
            Historical::select('id')
                ->whereNull('ends_at')
                ->whereColumn('person_id', 'persons.id')
                ->limit(1)
        );
    }

    public function scopeByMarketSelected($query)
    {
        if (Cache::has('market' . auth()->user()->id)) {
            return $query->doesntHave('historicals')->orWhereHas('historicals', function ($query) {
                $query->where('market_id', Cache::get('market' . auth()->user()->id));
            });
        } else {
            return $query->doesntHave('historicals')->orWhereHas('historicals', function ($query) {
                $query->whereIn('market_id', backpack_user()->my_markets->pluck('id')->toArray());
            });
        }
    }

    /**
     * Used with pivot historicals
     */
    public function scopePivotActiveTitular($query)
    {
        return $query->whereNull('historicals.ends_at')->where(function ($query) {
            $query->whereDate('historicals.end_vigencia', '>', now())->orWhereNull('historicals.end_vigencia');
        });
    }

    public function scopeUnsubscribed($query)
    {
        return $query->whereNotNull('unsubscribe_date');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('unsubscribe_date');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute($value)
    {
        return $this->getUrl($this->image);
    }

    public function getPdf1UrlAttribute($value)
    {
        return $this->getUrl($this->pdf1);
    }

    public function getLegaldDoc3Attribute($value)
    {
        return $this->getUrl($this->legal_doc3);
    }

    public function getPdf2UrlAttribute($value)
    {
        return $this->getUrl($this->pdf2);
    }

    public function getDniCodeAttribute($value)
    {
        if ($this->dni) {
            return $this->obfuscateDni($this->dni);
        }

        return '';
    }

    public function obfuscateDni($dni){
        $value = Str::substr($dni, 3, 4);
        return Str::padBoth($value, 11, '*');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "local";
        $destination_path = app('tenant')->name . "/person";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setPdf1Attribute($value)
    {
        $attribute_name = "pdf1";
        $disk = "local";
        $destination_path = app('tenant')->name . "/person";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setPdf2Attribute($value)
    {
        $attribute_name = "pdf2";
        $disk = "local";
        $destination_path = app('tenant')->name . "/person";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setSubstitute1DniImgAttribute($value)
    {
        $attribute_name = "substitute1_dni_img";
        $disk = "local";
        $destination_path = app('tenant')->name . "/person";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setSubstitute1ImgAttribute($value)
    {
        $attribute_name = "substitute1_img";
        $disk = "local";
        $destination_path = app('tenant')->name . "/person";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setSubstitute2DniImgAttribute($value)
    {
        $attribute_name = "substitute2_dni_img";
        $disk = "local";
        $destination_path = app('tenant')->name . "/person";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setSubstitute2ImgAttribute($value)
    {
        $attribute_name = "substitute2_img";
        $disk = "local";
        $destination_path = app('tenant')->name . "/person";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setRefDomiciliacionAttribute($value)
    {

        if (request()->iban) {
            //Si tenemos iban, pero ref_domiciliacion es null
            if (request()->iban && $this->ref_domiciliacion == NULL) {
                //Buscamos el numero de ref mas alto, el nuevo sera ese numero +1
                $person = Person::where('ref_domiciliacion', 'like', '%MC0%')->orderBy('ref_domiciliacion', 'DESC')->first();
                $this->attributes['ref_domiciliacion'] = 'MC0000000' . str_pad(ltrim(substr($person->ref_domiciliacion, -4, 4), '0') + 1, 4, "0", STR_PAD_LEFT);
            }
        } else {
            //Si el iban en null, significa que han eliminado el iban, o no introducido ninguno, por lo tanto la ref_domiciliacion tiene que ser NULL
            $this->attributes['ref_domiciliacion'] = NULL;
        }
    }

    public function setIbanAttribute($value)
    {
        //Si el nuevo valor de iban, es diferente al iban antiguo, entonces cambia el numero de referencia
        if ($value != $this->iban) {
            //Buscamos el numero de ref mas alto, el nuevo sera ese numero +1
            $person = Person::where('ref_domiciliacion', 'like', '%MC0%')->orderBy('ref_domiciliacion', 'DESC')->first();
            $this->attributes['ref_domiciliacion'] = 'MC0000000' . str_pad(ltrim(substr($person->ref_domiciliacion, -4, 4), '0') + 1, 4, "0", STR_PAD_LEFT);
        }

        //Si el iban es null, ref tiene que convertirse a null
        if (!$value) {
            $this->attributes['ref_domiciliacion'] = NULL;
        }

        $this->attributes['iban'] = $value;
    }

    public function setLegalDoc3Attribute($value)
    {
        $attribute_name = "legal_doc3";
        $disk = "local";
        $destination_path = app('tenant')->name . "/person/docs";
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        if (is_uploaded_file($value)) {
            $this->attributes['legal_doc3_user'] = backpack_user()->id;
            $this->attributes['legal_doc3_signature_date'] = now();
        }
    }
}
