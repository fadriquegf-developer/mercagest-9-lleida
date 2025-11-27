<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use Backpack\CRUD\app\Http\Controllers\Auth\LoginController as BackpackLoginController;

class LoginController extends BackpackLoginController
{
    use ListensForLdapBindFailure;

    public function __construct()
    {
        parent::__construct();

        $this->listenForLdapBindFailure();
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = [
            $this->username() => $request->input($this->username()),
            'password' => $request->password,
        ];

        if (app()->isLocal() || env('LDAP_FALLBACK_LOGIN')) {
            $credentials['fallback'] = [
                'email' => $request->input($this->username()),
                'password' => $request->password,
            ];
        }

        return $credentials;
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $eloquentUser = $user;
        // cehck if ldap is enable to prevent error
        $ldapUser = (app()->isLocal() || env('LDAP_FALLBACK_LOGIN')) ? null : $eloquentUser->ldap;

        if (!$ldapUser || !$ldapUser->groups()->contains(['CN=GRP_MERCATS_ADMIN,CN=Users,DC=paeria,DC=loc'])) {
            // User is not a member of the "administrator".
            return;
        }
    }
}
