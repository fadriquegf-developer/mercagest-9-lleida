<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuthProdRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AuthProdCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuthProdCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \App\Traits\AdminPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\AuthProd::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/auth-prod');
        $this->crud->setEntityNameStrings(__('backpack.auth_prods.single'), __('backpack.auth_prods.plural'));
        $this->setPermissions('auth_prods');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(['name' => 'id', 'label' => __('backpack.auth_prods.id')]);
        $this->crud->addColumn(['name' => 'name', 'label' => __('backpack.auth_prods.name')]);
        $this->crud->addColumn(['name' => 'sector_id', 'label' => __('backpack.auth_prods.sector_id')]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AuthProdRequest::class);

        $this->crud->addField(['name' => 'name', 'label' => __('backpack.auth_prods.name'), 'type' => 'text']);
        $this->crud->addField([
            'name' => 'sector_id',
            'label' => __('backpack.auth_prods.sector_id'),
            'type' => 'select',
            'model' => 'App\Models\Sector',
            'attribute' => 'name',
            'allows_null' => true,
            'allows_null_value' => __('backpack.auth_prods.sector_default'),
            'entity' => 'sector'
        ]); // notice the name is the foreign key attribute
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
}
