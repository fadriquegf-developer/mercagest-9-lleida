<?php

namespace App\Models;

use Backpack\PermissionManager\app\Models\Role as BackpackRole;

class Role extends BackpackRole
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
    /**
     * A role may be given various permissions.
     */
    public function visiblePermissions()
    {
        return $this->permissions()->visible();
    }

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
