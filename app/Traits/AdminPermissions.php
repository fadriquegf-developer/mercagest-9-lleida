<?php

namespace App\Traits;

trait AdminPermissions
{
    private function setPermissions($sectionName)
    {
        $this->crud->denyAccess(['show', 'list', 'create', 'update', 'delete']);

        $user = backpack_user();

        if ($user->hasPermissionTo($sectionName . '.show')) {
            $this->crud->allowAccess('show');
        }
        if ($user->hasPermissionTo($sectionName . '.list')) {
            $this->crud->allowAccess('list');
        }
        if ($user->hasPermissionTo($sectionName . '.create')) {
            $this->crud->allowAccess('create');
        }
        if ($user->hasPermissionTo($sectionName . '.update')) {
            $this->crud->allowAccess('update');
        }
        if ($user->hasPermissionTo($sectionName . '.delete')) {
            $this->crud->allowAccess('delete');
        }
    }

    private function checkUserAllowedMarkets($market_id)
    {
        if (!in_array($market_id, backpack_user()->my_markets->pluck('id')->toArray())) {
            abort(403);
        }
    }
}
