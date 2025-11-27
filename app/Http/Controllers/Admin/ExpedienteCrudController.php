<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExpedienteRequest;
use App\Models\Person;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExpedienteCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExpedienteCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Expediente::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/expediente');
        CRUD::setEntityNameStrings(__('backpack.expedientes.single'), __('backpack.expedientes.plural'));
        $this->setPermissions('expedientes');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->denyAccess('show');

        if (request()->has('person_id')) {
            session(['selectedPerson' => request()->input('person_id')]);
        } else {
            session()->forget('selectedPerson');
        }

        // load selected person
        $person_id = session('selectedPerson');
        if ($person_id) {
            $this->crud->addClause('where', 'person_id', $person_id);
        }

        $this->crud->addFilter([
            'name'  => 'person_id',
            'type'  => 'select2',
            'label' => __('backpack.expedientes.person_id'),
        ], function () {
            return Person::active()->get()->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'person_id', $value);
        });

        $this->crud->addColumn([
            'name'         => 'person',
            'type'         => 'relationship',
            'label'        => __('backpack.expedientes.person_id'),
            'entity'    => 'person',
            'attribute' => 'name',
            'model'     => App\Models\Person::class,
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('person', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            }
        ]);

        $this->crud->addColumn([
            'name'      => 'num_expediente',
            'label'     => __('backpack.expedientes.num_expediente'),
        ]);

        $this->crud->addColumn([
            'name'  => 'url',
            'label' => __('backpack.expedientes.url'),
            'type'  => 'url'
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
        CRUD::setValidation(ExpedienteRequest::class);

        $this->crud->addFields([
            [
                'label'     => __('backpack.expedientes.person_id'),
                'type'      => 'select2',
                'name'      => 'person_id',
                'entity'    => 'person',
                'model'     => "App\Models\Person",
                'attribute' => 'name',
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->get();
                }),
                'default' => session('selectedPerson')
            ],
            [
                'name' => 'num_expediente',
                'label' => __('backpack.expedientes.num_expediente')
            ],
            [
                'name' => 'url',
                'label' => __('backpack.expedientes.url'),
            ]
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
