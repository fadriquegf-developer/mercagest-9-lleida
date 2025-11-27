<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BicConversionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BicConversionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BicConversionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\BicConversion::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/bic-conversion');
        CRUD::setEntityNameStrings('codi BIC', 'codis BIC');
        $this->setPermissions('bic');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        CRUD::addColumn([
            'label' => 'Codi Banc',
            'name' => 'bank_code', 
            'type' => 'text'
        ]); 

        CRUD::addColumn([
            'label' => 'Nom Banc',
            'name' => 'bank_title', 
            'type' => 'text'
        ]); 

        CRUD::addColumn([
            'label' => 'Codi BIC',
            'name' => 'bank_bic', 
            'type' => 'text'
        ]); 
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
        CRUD::setValidation(BicConversionRequest::class);

        
        CRUD::addField([
            'label' => 'Codi Banc',
            'name' => 'bank_code', 
            'type' => 'text'
        ]); 

        CRUD::addField([
            'label' => 'Nom Banc',
            'name' => 'bank_title', 
            'type' => 'text'
        ]); 

        CRUD::addField([
            'label' => 'Codi BIC',
            'name' => 'bank_bic', 
            'type' => 'text'
        ]); 


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
}
