<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MarketRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Cache;

/**
 * Class MarketCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MarketCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\Market::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/market');
        $this->crud->setEntityNameStrings(__('backpack.markets.single'), __('backpack.markets.plural'));
        $this->setPermissions('markets');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn(['name' => 'id', 'label' => __('backpack.markets.id')]);
        $this->crud->addColumn([
            'name'  => 'status',
            'label' => 'Estat',
            'type'  => 'boolean',
            'options' => [0 => 'Inactiu', 1 => 'Actiu'],
            'wrapper' => [
                'element' => 'span',
                'class' => function ($crud, $column, $entry, $related_key) {
                    return $entry->status ? 'badge badge-success p-2' : 'badge badge-danger p-2';
                },
            ]
        ]);
        $this->crud->addColumn(['name' => 'name', 'label' => __('backpack.markets.name')]);
        $this->crud->addColumn(['name' => 'town_id', 'label' => __('backpack.markets.town_id')]);
        $this->crud->addColumn([
            'name' => 'rate_id',
            'label' => __('backpack.markets.rates'),
            'entity'    => 'rates',
            'attribute' => 'name',
            'model'     => 'App\Models\Rate'
        ]);
        $this->crud->addColumn([
            'name'  => 'days_of_week', // The db column name
            'label' => __('backpack.markets.days_of_week'), // Table column heading
            'type'    => 'select_from_array',
            'options' => __('backpack.markets.option_days_of_week'),
        ]);

        $this->crud->addButtonFromView('line', 'calendar', 'calender', 'beginning');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MarketRequest::class);

        $this->crud->addFields([
            [
                'name'  => 'status',
                'type'  => 'switch',
                'label'    => 'Activar/Desactivar',
                'color'    => 'primary',
                'onLabel' => '✓',
                'offLabel' => '✕',
            ],
            ['name' => 'name', 'label' => __('backpack.markets.name'), 'type' => 'text'],
            [
                'name' => 'town_id',
                'label' => __('backpack.markets.town_id'),
                'type' => 'select',
                'model' => 'App\Models\Town',
                'attribute' => 'name',
                'entity' => 'town',
                'allows_null_value' => __('backpack.markets.town_default'),
                'allows_null' => true,
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->get();
                }),
            ],
            [
                'label' => __('backpack.markets.rates'),
                'type' => 'select',
                'name' => 'rate_id',
                'entity' => 'rates',
                'model' => "App\Models\Rate",
                'attribute' => 'name',
                'pivot' => false,
                'allows_null_value' => __('backpack.markets.rates_default'),
                'allows_null' => true,
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->get();
                }),
            ],
            [
                'name' => 'days_of_week',
                'label' => __('backpack.markets.days_of_week'),
                'type' => 'select2_from_array',
                'options' => __('backpack.markets.option_days_of_week'),
                'allows_null' => false,
                'default' => 'one',
                'allows_multiple' => true,
            ]

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

    public function setMarketAction($id)
    {
        self::setMarket($id);

        $url = url()->previous();
        $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();

        if ($route == "/market/{market_id}/calendar.index") {
            //Do required things
            return redirect()->route('/market/{market_id}/calendar.index', ['market_id' => $id]);
        }

        return redirect()->back();
    }

    public static function isMarketSelected()
    {
        return Cache::has('market' . auth()->user()->id);
    }

    public static function setMarket($id)
    {
        if (is_numeric($id) && !in_array($id, backpack_user()->my_markets->pluck('id')->toArray())) {
            abort(403);
        }

        Cache::forget('market' . auth()->user()->id);
        if (is_numeric($id)) {
            Cache::forever('market' . auth()->user()->id, $id);
        }
    }
}
