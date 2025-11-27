<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, CrudTrait, AuthenticatesWithLdap, HasLdapUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'signature'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['market', 'markets', 'market_days'];

    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($obj) {
            $obj->my_markets()->detach();
        });
    }

    public function hasMarketSelect()
    {
        return (bool) $this->market;
    }

    public function my_markets()
    {
        return $this->belongsToMany(Market::class)->active()->withTimestamps()->orderBy('name');
    }

    protected function market(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->my_markets()
                ->where('markets.id', Cache::get('market' . auth()->user()->id))
                ->first()
                ->name ?? '',
        );
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

    public function guardName()
    {
        return backpack_guard_name();
    }

    protected function markets(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->my_markets,
        );
    }

    public function getMarketDaysAttribute()
    {
        $market = $this->my_markets()
            ->where('markets.id', Cache::get('market' . auth()->user()->id))
            ->first();
        $market_days = [];
        if ($market && is_array($market->days_of_week)) {
            foreach ($market->days_of_week as $day) {
                $market_days[] = __('backpack.markets.option_days_of_week')[$day];
            }
        }
        return implode(', ', $market_days);
    }

    public function setSignatureAttribute($value)
    {
        $attribute_name = 'signature';
        if (is_uploaded_file($value)) {
            $disk = 'local';
            $destination_path = app('tenant')->name.'/user';
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        } else {
            // Si no es uploaded file, viene de migracion
            $this->attributes[$attribute_name] = $value;
        }
    }

}
