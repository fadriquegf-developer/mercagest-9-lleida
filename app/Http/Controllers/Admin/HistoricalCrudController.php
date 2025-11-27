<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HistoricalRequest;
use App\Models\Market;
use App\Models\Person;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HistoricalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HistoricalCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    //use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\AdminPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Historical::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/historical');
        $this->crud->setEntityNameStrings(__('backpack.historicals.single'), __('backpack.historicals.plural'));
        $this->setPermissions('historicals');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->enableExportButtons();
        $this->crud->addClause('ByMarketSelected');

        $this->crud->addFilter([
            'name'  => 'market',
            'type'  => 'select2',
            'label' => 'Mercat'
        ], function () {
            return Market::whereIn('id', backpack_user()->my_markets->pluck('id')->toArray())->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->query->whereHas('stall', function ($query) use ($value) {
                $query->where('market_id', $value);
            });
        });

        $this->crud->addFilter([
            'name'  => 'person',
            'type'  => 'select2',
            'label' => 'Paradista'
        ], function () {
            return Person::get()->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'person_id', $value);
        });

        $this->crud->addColumn(['name' => 'id', 'label' => __('backpack.historicals.id')]);
        $this->crud->addColumn([
            'name' => 'person_id',
            'label' => __('backpack.historicals.person_id'),
            'type'      => 'select',
            'name'      => 'person_id',
            'entity'    => 'person',
            'attribute' => 'name',
            'model'     => Person::class,
        ]);
        $this->crud->addColumn([
            'name' => 'stall_id',
            'label' => __('backpack.historicals.stall_id'),
            'type' => 'select',
            'entity' => 'stall',
            'attribute' => 'num',
            'model' => "App\Models\Stall",
        ]);
        $this->crud->addColumn(['name' => 'market_name', 'label' => __('backpack.markets.single')]);
        $this->crud->addColumn(['name' => 'start_at', 'label' => __('backpack.historicals.start_at'), 'type' => 'date']);
        $this->crud->addColumn(['name' => 'end_vigencia', 'label' => __('backpack.historicals.end_vigencia'), 'type' => 'date']);
        $this->crud->addColumn(['name' => 'ends_at', 'label' => __('backpack.historicals.ends_at'), 'type' => 'date']);

        $this->crud->addFilter(
            [
                'type'  => 'date_range',
                'name'  => 'from_to',
                'label' => __('backpack.historicals.date_range')
            ],
            false,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->query->where(function ($query) use ($dates) {
                    $query->where(function ($query) use ($dates) {
                        $query->whereDate('start_at', '>=', $dates->from)->whereDate('start_at', '<=', $dates->to . ' 23:59:59');
                    })->orWhere(function ($query) use ($dates) {
                        $query->whereDate('end_vigencia', '>=', $dates->from)->whereDate('end_vigencia', '<=', $dates->to . ' 23:59:59');
                    })->orWhere(function ($query) use ($dates) {
                        $query->whereDate('ends_at', '>=', $dates->from)->whereDate('ends_at', '<=', $dates->to . ' 23:59:59');
                    });
                });
            }
        );
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(HistoricalRequest::class);

        $this->crud->addFields([
            /*             [
                'name' => 'stall_id',
                'label' => __('backpack.historicals.stall_id'),
                'type' => 'select',
                'model' => 'App\Models\Stall',
                'attribute' => 'num_market',
                'entity' => 'stall'
            ],
            [
                'name' => 'person_id',
                'label' => __('backpack.historicals.person_id'),
                'type' => 'select',
                'model' => 'App\Models\Person',
                'attribute' => 'name',
                'entity' => 'person',
                'default' => request()->input('person_id') ?: '',
            ], */
            /* [
                'name' => 'family_transfer',
                'label' => __('backpack.historicals.family_transfer'),
                'type' => 'select_from_array',
                'options' => __('backpack.historicals.family_transfer_options')
            ], */
            /* ['name' => 'start_at', 'label' => __('backpack.historicals.start_at'), 'type' => 'date'], */
            [   // CustomHTML
                'name'  => 'info',
                'type'  => 'custom_html',
                'value' => '<div class="alert alert-danger"><b>ATENCIÓ!</b> Només modificar les següents dates en cas de ser realment necessari</div>'
            ],
            ['name' => 'end_vigencia', 'label' => __('backpack.historicals.end_vigencia'), 'type' => 'date'],
            ['name' => 'ends_at', 'label' => __('backpack.historicals.ends_at'), 'type' => 'date']
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
        $entry = $this->crud->getCurrentEntry();
        $person = $entry->person;
        $stall = $entry->stall;

        $this->crud->addColumns([
            ['name' => 'id', 'label' => __('backpack.historicals.id')],
            [
                'name'     => 'person_name',
                'label'    => __('backpack.historicals.person_id'),
                'type'     => 'closure',
                'function' => function ($entry) use ($person) {
                    return $person->name ?? '-';
                },
                'wrapper'   => [
                    'href' => function ($crud, $column, $entry, $related_key) use ($person) {
                        return backpack_url('person/' . $person->id . '/show');
                    },
                ],
            ],
            [
                'name'     => 'person_email',
                'label'    => __('backpack.persons.email'),
                'type'     => 'closure',
                'function' => function ($entry) use ($person) {
                    return $person->email ?? '-';
                }
            ],
            [
                'name'     => 'person_phone',
                'label'    => __('backpack.persons.phone'),
                'type'     => 'closure',
                'function' => function ($entry) use ($person) {
                    return $person->phone ?? '-';
                }
            ],
            [
                'name' => 'stall_id',
                'label' => __('backpack.historicals.stall_id'),
                'type'     => 'closure',
                'function' => function ($entry) use ($stall) {
                    return $stall->num ?? '-';
                }
            ],
            [
                'name' => 'trimestral',
                'label' => 'Trimestres de Pagament',
                'type'     => 'closure',
                'function' => function ($entry) use ($stall) {
                    $table = '';
                    $table = '<table class="table table-bordered table-condensed table-striped m-b-0">';
                    $table .= '<tbody>';
                    for ($i = 1; $i <= 4; $i++) {
                        $table .= '<td>Trimestre ' . $i . ' <i class="la ' . ($stall->{'trimestral_' . $i} ? 'la-check-circle' : 'la-circle') . '"></i></td>';
                    }
                    $table .= '</tbody></table>';
                    return $table;
                },
                'escaped' => false,
            ],
            ['name' => 'market_name', 'label' => __('backpack.markets.single')],
            ['name' => 'start_at', 'label' => __('backpack.historicals.start_at'), 'type' => 'date'],
            ['name' => 'end_vigencia', 'label' => __('backpack.historicals.end_vigencia'), 'type' => 'date'],
            ['name' => 'ends_at', 'label' => __('backpack.historicals.ends_at'), 'type' => 'date'],
            [
                'name' => 'family_transfer',
                'label' => __('backpack.historicals.family_transfer'),
                'type' => 'select_from_array',
                'options' => __('backpack.historicals.family_transfer_options')

            ],
            [
                'name' => 'reason',
                'label' => __('backpack.historicals.reason'),
                'type' => 'select_from_array',
                'options' => \App\Models\Reason::get()->pluck('title', 'slug')->toArray()

            ],
            [
                'name' => 'explained_reason',
                'label' => __('backpack.stalls.explained_reason'),
                'limit' => 50000
            ],
        ]);
    }
}
