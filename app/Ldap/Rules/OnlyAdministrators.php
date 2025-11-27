<?php

namespace App\Ldap\Rules;

use LdapRecord\Laravel\Auth\Rule;
use LdapRecord\Models\ActiveDirectory\Group;

class OnlyAdministrators extends Rule
{
    public function isValid()
    {
        $administrators = Group::find('CN=GRP_MERCATS_ADMIN,CN=Users,DC=paeria,DC=loc');

        return $this->user->groups()->recursive()->exists($administrators);
    }
}