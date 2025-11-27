<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\Settings\app\Http\Controllers\SettingCrudController as BackpackSettingCrudController;

class SettingCrudController extends BackpackSettingCrudController
{
    use \App\Traits\AdminPermissions;

    public function setup()
    {
        $this->crud->setModel(Setting::class);
        $this->crud->setEntityNameStrings(trans('backpack::settings.setting_singular'), trans('backpack::settings.setting_plural'));
        $this->crud->setRoute(backpack_url(config('backpack.settings.route')));
        $this->crud->orderBy('id');
        $this->setPermissions('settings');
    }

    public function setupListOperation()
    {
        // only show settings which are marked as active
        CRUD::addClause('where', 'active', 1);

        // columns to show in the table view
        CRUD::setColumns([
            [
                'name'  => 'name',
                'label' => trans('backpack::settings.name'),
            ],
            [
                'name'  => 'description',
                'label' => trans('backpack::settings.description'),
                'limit' => 500
            ],
            [
                'name'  => 'value',
                'label' => trans('backpack::settings.value'),
            ],
        ]);
    }
}
