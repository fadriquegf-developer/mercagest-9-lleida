<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RateCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\Rate::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/rate');
        $this->crud->setEntityNameStrings(__('backpack.rates.single'), __('backpack.rates.plural'));
        $this->setPermissions('rates');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(['name' => 'name', 'label' =>  __('backpack.rates.single')]);
        $this->crud->addColumn(['name' => 'rate_type', 'label' => __('backpack.rates.rate_type'), 'type' => 'select_from_array', 'options'=> ['daily' => 'Tarifa per Día', 'fixed' => 'Tarifa Fixe']]);
        $this->crud->addColumn(['name' => 'price', 'label' => __('backpack.rates.price')]);
        $this->crud->addColumn(['name' => 'price_type', 'label' => __('backpack.rates.price_type'), 'type' => 'select_from_array', 'options'=> ['fixed' => 'Preu Fix', 'meters' => 'Per Metres']]);
        $this->crud->addColumn(['name' => 'price_expenses', 'label' => __('backpack.rates.price_expenses')]);
        $this->crud->addColumn(['name' => 'price_expenses_type', 'label' => __('backpack.rates.price_expenses_type'), 'type' => 'select_from_array', 'options' => ['fixed' => 'Preu Fix', 'meters' => 'Per Metres']]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(RateRequest::class);

        $this->crud->addField(['name' => 'name', 'label' => __('backpack.rates.single')]);
        $this->crud->addField([
            'name' => 'price', 
            'label' => __('backpack.rates.price'),
            'wrapper' => [
                'class' => 'form-group col-sm-9'
            ],
        ]);
        $this->crud->addField([
            'name' => 'price_type', 
            'label' => __('backpack.rates.price_type'),
            'type' => 'select_from_array',
            'options'     => ['fixed' => 'Preu Fix', 'meters' => 'Per Metres'],
            'wrapper' => [
                'class' => 'form-group col-sm-3'
            ],
        ]);
        $this->crud->addField([
            'name' => 'price_expenses', 
            'label' => __('backpack.rates.price_expenses'),
            'wrapper' => [
                'class' => 'form-group col-sm-9'
            ],
        ]);
        $this->crud->addField([
            'name' => 'price_expenses_type', 
            'label' => __('backpack.rates.price_expenses_type'),
            'type' => 'select_from_array',
            'options'     => ['fixed' => 'Preu Fix', 'meters' => 'Per Metres'],
            'wrapper' => [
                'class' => 'form-group col-sm-3'
            ],
        ]);

        $this->crud->addField([
            'name' => 'rate_type', 
            'label' => __('backpack.rates.rate_type'),
            'type' => 'select_from_array',
            'options'     => ['daily' => 'Tarifa per Día', 'fixed' => 'Tarifa Fixa'],
        ]);
        
        $this->crud->addField([ 
            'name'  => 'info_rate_type',
            'type'  => 'custom_html',
            'value' => "<div class='alert alert-info'><i class='las la-info-circle'></i> El <b>Tipus de tarifa</b> serveix per indicar si la tarifa tindrà en compte els dies de mercat que tingui la parada durant un mes (<b>Tarifa per Dia</b>) o si no tindrà en compte els dies i, per tant, la tarifa serà fixa independentment dels dies de mercat que hi hagin hagut durant un mes (<b>Tarifa Fixa</b>)</div>"
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
