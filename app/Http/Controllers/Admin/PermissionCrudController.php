<?php

namespace App\Http\Controllers\Admin;

use Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController as BackpackPermissionCrudController;

class PermissionCrudController extends BackpackPermissionCrudController
{
    use \App\Traits\AdminPermissions;

    public function setup()
    {
        parent::setup();

        $this->setPermissions('permissions');
    }
}
