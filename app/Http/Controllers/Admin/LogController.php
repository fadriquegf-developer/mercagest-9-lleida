<?php

namespace App\Http\Controllers\Admin;

use Backpack\LogManager\app\Http\Controllers\LogController as BackpackLogController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class LogController extends BackpackLogController
{
    public function setup()
    {
        parent::setup();

        CRUD::denyAccess(['list', 'create', 'update', 'delete']);

        if(backpack_user()->can('logs.manage')){
            CRUD::allowAccess(['list', 'create', 'update', 'delete']);
        }
    }
}
