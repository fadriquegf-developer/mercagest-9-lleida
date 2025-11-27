<?php

namespace App\Models;

use Backpack\PermissionManager\app\Models\Permission as BackpackPermission;

class Permission extends BackpackPermission
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeVisible($query)
    {
        return $query->where('hidden', false);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    /**
     * Translate name
     */
    protected function getShowNameAttribute($value)
    {
        $text = '';
        $value = $this->name;
        
        if ($value) {
            $aux = explode('.', $value);

            if (isset($aux[0])) { // entry
                $text = trans("backpack.{$aux[0]}.single");
            }

            if (isset($aux[1]) && trans()->has('backpack.permissions.' . $aux[1])) {
                $text .= ': ' . trans('backpack.permissions.' . $aux[1]);
            }
        }

        return $text;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
