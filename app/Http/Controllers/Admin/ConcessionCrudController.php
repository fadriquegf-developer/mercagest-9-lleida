<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ConcessionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ConcessionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ConcessionCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\Concession::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/concession');
        $this->crud->setEntityNameStrings(__('backpack.concessions.single'), __('backpack.concessions.plural'));
        $this->setPermissions('concessions');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(['name' => 'id', 'label' =>  __('backpack.concessions.id')]);
        $this->crud->addColumn([
            'label' => __('backpack.concessions.auth_prod_id'),
            'type' => 'select',
            'name' => 'auth_prod_id',
            'entity' => 'auth_prod',
            'attribute' => 'name',
            'model' => "App\Models\AuthProd",
        ]);
        $this->crud->addColumn([
            'label' => __('backpack.concessions.stall_id'),
            'type' => 'select',
            'name' => 'stall_id',
            'entity' => 'stall',
            'attribute' => 'num',
            'model' => "App\Models\Stall",
        ]);
        $this->crud->addColumn(['name' => 'start_at', 'label' => __('backpack.concessions.start_at')]);
        $this->crud->addColumn(['name' => 'end_at', 'label' => __('backpack.concessions.end_at')]);
        $this->crud->addColumn(['name' => 'pdf', 'label' => __('backpack.concessions.pdf')]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ConcessionRequest::class);

        $this->crud->addField([
            'name' => 'auth_prod_id',
            'label' => __('backpack.concessions.auth_prod_id'),
            'type' => 'select',
            'model' => 'App\Models\AuthProd',
            'attribute' => 'name',
            'entity' => 'auth_prod'
        ]);
        $this->crud->addField([
            'name' => 'stall_id',
            'label' => __('backpack.concessions.stall_id'),
            'type' => 'select',
            'model' => 'App\Models\Stall',
            'attribute' => 'num',
            'entity' => 'stall'
        ]);
        $this->crud->addField(['name' => 'start_at', 'label' => __('backpack.concessions.start_at'), 'type' => 'date']);
        $this->crud->addField(['name' => 'end_at', 'label' => __('backpack.concessions.end_at'), 'type' => 'date']);
        $this->crud->addField(['name' => 'pdf', 'label' => __('backpack.concessions.pdf'), 'type' => 'upload', 'upload' => true]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
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
