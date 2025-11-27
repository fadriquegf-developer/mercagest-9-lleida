<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BonusRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Bonus;
use App\Models\Calendar;
use App\Models\Stall;
use Illuminate\Http\Request;

/**
 * Class BonusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BonusCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\AdminPermissions;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation {
        bulkDelete as traitBulkDelete;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Bonus::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/bonus');
        $this->crud->setEntityNameStrings(__('backpack.bonuses.single'), __('backpack.bonuses.plural'));
        $this->setPermissions('bonuses');
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
            [
                'name'      => 'stall_id',
                'label'     => __('backpack.bonuses.stall_id'),
                'type'      => 'select',
                'entity'    => 'stall',
                'model'     => "App\Models\Stall",
                'attribute' => 'num'
            ],
            [
                'name'      => 'market_id',
                'label'     => __('backpack.bonuses.market_id'),
                'type'      => 'select',
                'entity'    => 'stall',
                'model'     => "App\Models\Stall",
                'attribute' => 'market_name'
            ],
            [
                'name' => 'type',
                'label' => __('backpack.bonuses.amount_type'),
                'type' => 'select_from_array',
                'options' => __('backpack.bonuses.amount_types'),
                'allow_null' => false
            ],
            [
                'name' => 'amount',
                'label' => __('backpack.bonuses.amount'),
                'type' => 'number',
            ],
            [
                'name' => 'start_at',
                'label' => __('backpack.bonuses.start_at'),
                'type' => 'date',
            ],
            [
                'name' => 'ends_at',
                'label' => __('backpack.bonuses.ends_at'),
                'type' => 'date',
            ],
            [
                'name' => 'title',
                'label' => __('backpack.bonuses.title'),
                'type' => 'text',
            ],
            [
                'name' => 'reason',
                'label' => __('backpack.bonuses.reason'),
                'type' => 'textarea',
            ]
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
        $this->crud->setValidation(BonusRequest::class);

        $this->crud->addFields([
            [
                'name' => 'select',
                'label' => __('backpack.bonuses.type'),
                'type' => 'select_from_array',
                'options' => __('backpack.bonuses.types'),
                'allows_null' => false
            ],
            [
                'name'        => 'market_id',
                'label'       => __('backpack.stalls.market'),
                'type'        => 'select_from_array',
                'options'     => \App\Models\Market::where('status', 1)->get()->pluck('name', 'id'),
                'allows_null' => true,
            ],
            [
                'name'        => 'marketgroup_id',
                'label'       => __('backpack.stalls.market_group'),
                'type'        => 'select_from_array',
                'options'     => \App\Models\MarketGroup::where('status', 1)->get()->pluck('title', 'id'),
                'allows_null' => true,
            ],
            [
                'name'      => 'stall_id',
                'label'     => __('backpack.bonuses.stall_id'),
                'type'      => 'select2',
                'entity'    => 'stall',
                'model'     => "App\Models\Stall",
                'attribute' => 'num_market_active_titular',
                'allows_null' => true
            ],
            [
                'name' => 'start_at',
                'label' => __('backpack.bonuses.start_at'),
                'type' => 'date',
            ],
            [
                'name' => 'ends_at',
                'label' => __('backpack.bonuses.ends_at'),
                'type' => 'date',
            ],
            [
                'name' => 'type',
                'label' => __('backpack.bonuses.amount_type'),
                'type' => 'select_from_array',
                'options' => __('backpack.bonuses.amount_types'),
                'allows_null' => false

            ],
            [
                'name' => 'amount',
                'label' => __('backpack.bonuses.amount'),
                'type' => 'number',
                'attributes' => [
                    'min' => 0,
                    'step' => '0.01'
                ]
            ],
            [   // CustomHTML
                'name'  => 'info',
                'type'  => 'custom_html',
                'value' => '<div class="alert alert-info" style="display:none" id="info-market-days">Te un máxim de <span></span> dies per bonificar</div>'
            ],
            [
                'name' => 'title',
                'label' => __('backpack.bonuses.title'),
                'type' => 'text',
            ],
            [
                'name' => 'reason',
                'label' => __('backpack.bonuses.reason'),
                'type' => 'textarea',
            ],
            [
                'name' => 'script',
                'type' => 'view',
                'view' => 'admin/bonus/script'
            ],
        ]);
    }

    public function store(BonusRequest $request)
    {

        //Si hemos seleccionado un mercado, buscamos todos los puesto del mercado i el grupo de mercado
        if ($request->input('select') === 'market') {
            $stalls = Stall::where('market_id', $request->market_id);

            //Si ha seleccionado tambien un grupo de mercado, filtramos el grupo de mercado dentro de los ya filtrados por mercado
            if ($request->input('marketgroup_id')) {
                $stalls = $stalls->where('market_group_id', $request->marketgroup_id);
            }

            //Obtenemos los que tienen un titular activo
            $stalls = $stalls->activeTitular()->get();

            foreach ($stalls as $stall) {
                Bonus::create([
                    'stall_id' => $stall->id,
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'start_at' => $request->start_at,
                    'ends_at' => $request->ends_at,
                    'title' => $request->title,
                    'reason' => $request->reason
                ]);
            }

            return redirect('/admin/bonus');

            //Si hemos seleccionado un grupo de mercado, buscamos todos los puesto del grupo de mercado que tengan un titular activo
        } else if ($request->input('select') === 'group') {
            $stalls = Stall::where('market_group_id', $request->marketgroup_id)->activeTitular()->get();

            foreach ($stalls as $stall) {
                Bonus::create([
                    'stall_id' => $stall->id,
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'start_at' => $request->start_at,
                    'ends_at' => $request->ends_at,
                    'title' => $request->title,
                    'reason' => $request->reason
                ]);
            }

            return redirect('/admin/bonus');
        } else {
            $response = $this->traitStore();
            return $response;
        }
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

        // remove fields only used on create
        $this->crud->removeField('select');
        $this->crud->removeField('marketgroup_id');
    }

    public function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function getMarketDaysByStall(Request $request)
    {
        $stall = Stall::find($request->stall_id);

        //Obtenemos los dias de mercado de cada puesto filtrando por las fechas
        $days = Calendar::filterByMarket($stall->market->id)->filterByDateRange($request->start_at, $request->end_at)->get();

        return [
            'status' => 'success',
            'data' => [
                'num_days' => count($days)
            ]
        ];
    }

    public function getMarketDaysByMarket(Request $request)
    {
        //Obtenemos los dias de mercado de cada puesto filtrando por las fechas
        $days = Calendar::filterByMarket($request->market_id)->filterByDateRange($request->start_at, $request->end_at)->get();

        return [
            'status' => 'success',
            'data' => [
                'num_days' => count($days)
            ]
        ];
    }

    /**
     * Delete multiple entries in one go.
     *
     * @return string
     */
    public function bulkDelete()
    {
        $this->crud->hasAccessOrFail('bulkDelete');

        $entries = request()->input('entries', []);
        $deletedEntries = [];
        $elementNotDelete = false;

        foreach ($entries as $key => $id) {
            if ($entry = $this->crud->model->find($id)) {
                $deletedEntries[] = $entry->delete();
            }
        }

        if ($elementNotDelete) {
            return response([
                'title' => 'Error',
                'message' => 'Element no eliminat'
            ], '403');
        }

        return $deletedEntries;
    }
}
