<?php

namespace App\Http\Controllers\Admin;

use Backpack\PermissionManager\app\Http\Requests\RoleStoreCrudRequest as StoreRequest;
use Backpack\PermissionManager\app\Http\Requests\RoleUpdateCrudRequest as UpdateRequest;
use Backpack\PermissionManager\app\Http\Controllers\RoleCrudController as BackpackRoleCrudController;

class RoleCrudController extends BackpackRoleCrudController
{
    use \App\Traits\AdminPermissions;

    public function setup()
    {
        parent::setup();

        $this->setPermissions('roles');
    }

    public function setupListOperation()
    {
        /**
         * Show a column for the name of the role.
         */
        $this->crud->addColumn([
            'name'  => 'name',
            'label' => trans('backpack::permissionmanager.name'),
            'type'  => 'text',
        ]);

        /**
         * Show a column with the number of users that have that particular role.
         *
         * Note: To account for the fact that there can be thousands or millions
         * of users for a role, we did not use the `relationship_count` column,
         * but instead opted to append a fake `user_count` column to
         * the result, using Laravel's `withCount()` method.
         * That way, no users are loaded.
         */

        /* Bug whit ldap */
        // $this->crud->query->withCount('users'); 
        // $this->crud->addColumn([
        //     'label'     => trans('backpack::permissionmanager.users'),
        //     'type'      => 'text',
        //     'name'      => 'users_count',
        //     'wrapper'   => [
        //         'href' => function ($crud, $column, $entry, $related_key) {
        //             return backpack_url('user?role=' . $entry->getKey());
        //         },
        //     ],
        //     'suffix'    => ' ' . strtolower(trans('backpack::permissionmanager.users')),
        // ]);

        /**
         * In case multiple guards are used, show a column for the guard.
         */
        if (config('backpack.permissionmanager.multiple_guards')) {
            $this->crud->addColumn([
                'name'  => 'guard_name',
                'label' => trans('backpack::permissionmanager.guard_type'),
                'type'  => 'text',
            ]);
        }

        /**
         * Show the exact permissions that role has.
         */
        // $this->crud->addColumn([
        //     // n-n relationship (with pivot table)
        //     'label'     => mb_ucfirst(trans('backpack::permissionmanager.permission_plural')),
        //     'type'      => 'select_multiple',
        //     'name'      => 'permissions', // the method that defines the relationship in your Model
        //     'entity'    => 'permissions', // the method that defines the relationship in your Model
        //     'attribute' => 'name', // foreign key attribute that is shown to user
        //     'model'     => $this->permission_model, // foreign key model
        //     'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
        // ]);
    }

    public function setupCreateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(StoreRequest::class);

        //otherwise, changes won't have effect
        \Cache::forget('spatie.permission.cache');
    }

    public function setupUpdateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(UpdateRequest::class);

        //otherwise, changes won't have effect
        \Cache::forget('spatie.permission.cache');
    }

    private function addFields()
    {
        $this->crud->addField([
            'name'  => 'name',
            'label' => trans('backpack::permissionmanager.name'),
            'type'  => 'text',
        ]);

        if (config('backpack.permissionmanager.multiple_guards')) {
            $this->crud->addField([
                'name'    => 'guard_name',
                'label'   => trans('backpack::permissionmanager.guard_type'),
                'type'    => 'select_from_array',
                'options' => $this->getGuardTypes(),
            ]);
        }

        $this->crud->addField([
            'label'     => mb_ucfirst(trans('backpack::permissionmanager.permission_plural')),
            'type'      => 'checklist',
            'name'      => 'permissions',
            'entity'    => 'visiblePermissions',
            'attribute' => 'showName',
            'model'     => $this->permission_model,
            'pivot'     => true,
            'options' => (function ($query) {
                return $query->visible()->get()->pluck('showName', 'id')->toArray();
            }),
        ]);
    }
}
