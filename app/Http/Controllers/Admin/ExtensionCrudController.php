<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExtensionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExtensionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExtensionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\AdminPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Extension::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/extension');
        $this->crud->setEntityNameStrings(__('backpack.extensions.single'), __('backpack.extensions.plural'));
        $this->setPermissions('extensions');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumns([
            ['name' => 'stall.num_market_active_titular', 'label' => __('backpack.extensions.stall_id')],
            ['name' => 'stall.titular', 'label' => __('backpack.extensions.person_id')],
            ['name' => 'extension', 'label' => __('backpack.extensions.extension')],
            ['name' => 'description', 'label' => __('backpack.extensions.description')],
            ['name' => 'length', 'label' => __('backpack.extensions.length')],
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ExtensionRequest::class);

        $this->crud->addFields([
           [
              'label'     => __('backpack.extensions.stall_id'),
              'type'      => 'select',
              'name'      => 'stall_id',
              'entity'    => 'stall',
              'model'     => "App\Models\Stall",
              'attribute' => 'num_market_active_titular',
              'options'   => (function ($query) {
                   return $query->whereHas('historicals')->get();
               }),
           ],
            ['name' => 'extension', 'label' => __('backpack.extensions.extension'), 'type' => 'number'],
            ['name' => 'description', 'label' => __('backpack.extensions.description'), 'type' => 'textarea'],
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function setupShowOperation()
    {
        $this->setupListOperation();
    }
}
