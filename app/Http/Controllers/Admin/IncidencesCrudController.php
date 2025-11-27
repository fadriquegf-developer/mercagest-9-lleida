<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IncidencesRequest;
use App\Mail\SendIncidenceEmail;
use App\Models\Calendar;
use App\Models\Incidences;
use App\Models\Stall;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

/**
 * Class IncidencesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class IncidencesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \App\Traits\AdminPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Incidences::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/incidences');
        $this->crud->setEntityNameStrings(__('backpack.incidences.single'), __('backpack.incidences.plural'));
        // $this->crud->setListView('admin.incidence.list'); //view with calendar disabled
        $this->setPermissions('incidences');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addButtonFromModelFunction('top', 'contact_email', 'contactEmail', 'end');

        $this->crud->addClause('ByMarketSelected');

        $this->showCalendarIncidence();
        $this->crud->enableExportButtons();

        $this->crud->addColumns([
            [
                'name' => 'type',
                'label' => __('backpack.incidences.type'),
                'type' => 'select_from_array',
                'options' => __('backpack.incidences.types'),
            ],
            [
                'name' => 'title',
                'label' => __('backpack.incidences.title'),
            ],
            [
                'name' => 'date_incidence',
                'label' => __('backpack.incidences.date_incidence'),
                'type' => 'date'
            ],
            [
                'name' => 'send_at',
                'label' => __('backpack.incidences.send_at'),
                'type' => 'date'
            ],
            [
                'name' => 'person_id',
                'label' => __('backpack.absences.person_id'),
                'type'      => 'select',
                'name'      => 'person_id',
                'entity'    => 'person',
                'attribute' => 'name',
            ],
            [
                'name' => 'stall_id',
                'label' => __('backpack.absences.stall_id'),
                'type'      => 'select',
                'name'      => 'stall_id',
                'entity'    => 'stall',
                'attribute' => 'num_market',
            ],
            [
                'name' => 'status',
                'label' => __('backpack.incidences.status'),
                'type' => 'select_from_array',
                'options' => [
                    'pending' => __('backpack.incidences.statuses.pending'),
                    'solved' => __('backpack.incidences.statuses.solved')
                ],
                'wrapper' => [
                    'element' => 'span',
                    'class' => function ($crud, $column, $entry, $related_key) {
                        if ($entry->status == 'solved') return 'alert alert-success p-2 m-0';
                        return 'alert alert-warning p-2 m-0';
                    },
                ],
            ],
            [
                'name' => 'contact_email_id',
                'label' => __('backpack.incidences.contact_email_id'),
            ],
        ]);

        $this->crud->addButtonFromView('line', 'show', 'show_new_tab', 'beginning');
    }

    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $entry = $this->crud->getModel()->findOrFail($id);
        $pdf = $entry->generatePdf();

        return $pdf->stream();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(IncidencesRequest::class);

        $this->crud->addFields([
            [
                'name' => 'type',
                'label' => __('backpack.incidences.type'),
                'type' => 'select_from_array',
                'options' => __('backpack.incidences.types'),
                'allows_null' => false,
            ],
            [
                'name'      => 'stall_id',
                'label'     => __('backpack.stalls.single'),
                'type'      => 'select2',
                'entity'    => 'stall',
                'model'     => "App\Models\Stall",
                'attribute' => 'num_market_active_titular',
                'options'   => (function ($query) {
                    return $query->filterByMarket()->activeTitular()->get();
                }),
                'value' => request()->get('stall_id') ?: ''
            ],
            [
                'name' => 'date_incidence',
                'label' => __('backpack.incidences.date_incidence'),
                'type' => 'date',
                'default' => isset(request()->date) ? request()->date : ''
            ],

            [
                'name' => 'title',
                'label' => __('backpack.incidences.title'),
                'type' => 'text',
                'wrapper'   => [
                    'class' => 'form-group col-sm-12 required'
                ]
            ],
            [
                'name'        => 'title_owner_incidence',
                'label'       => __('backpack.incidences.title'),
                'type'        => 'select_from_array',
                'options'     => [
                    'Absència del titular de la parada' => 'Absència del titular de la parada',
                    'Absència de la parada' => 'Absència de la parada',
                    'Manca de llicència' => 'Manca de llicència',
                    'Manca de l\'acreditació de la llicència' => 'Manca de l\'acreditació de la llicència',
                    'No recollir la brossa de la parada' => 'No recollir la brossa de la parada',
                    'Incompliment de l\'horari' => 'Incompliment de l\'horari',
                    'Venda de producte no propi' => 'Venda de producte no propi',
                    'Venda de producte no autoritzat' => 'Venda de producte no autoritzat',
                    'Venda de productes alimentaris que no estan en condicions optimes pel seu consum' => 'Venda de productes alimentaris que no estan en condicions optimes pel seu consum',
                    'Ocupació d\'espai no propi' => 'Ocupació d\'espai no propi',
                    'Variació de l\'emplaçament de la parada' => 'Variació de l\'emplaçament de la parada',
                    'Circulació amb el vehicle fora de l\'horari establert' => 'Circulació amb el vehicle fora de l\'horari establert',
                    'Altres' => 'Altres',
                ],
                'allows_null' => false,
                'wrapper'   => [
                    'class' => 'form-group col-sm-12 required'
                ]
            ],
            [
                'name'        => 'title_general_incidence',
                'label'       => __('backpack.incidences.title'),
                'type'        => 'select_from_array',
                'options'     => [
                    'Edificacions' => 'Edificacions',
                    'Serveis' => 'Serveis',
                    'Via pública' => 'Via pública',
                    'Neteja' => 'Neteja',
                    'Seguretat i robatoris' => 'Seguretat i robatoris',
                    'Meteorològiques' => 'Meteorològiques',
                ],
                'allows_null' => false,
                'wrapper'   => [
                    'class' => 'form-group col-sm-12 required'
                ]
            ],

            [
                'name' => 'description',
                'label' => __('backpack.incidences.description'),
                'type' => 'textarea',
            ],
            [
                'name' => 'images',
                'label' => __('backpack.incidences.image'),
                'type'  => 'upload_multiple',
                'upload'    => true,
                'prefix' => config('backpack.base.route_prefix', 'admin') . '/storage/'
            ],
            [
                'name' => 'can_mount_stall',
                'label' => __('backpack.incidences.can_mount_stall'),
                'type'  => 'switch',
                'color'    => 'primary',
                'onLabel' => __('backpack.yes'),
                'offLabel' => __('backpack.no'),
                'wrapper' => [
                    'class' => 'form-group col-sm-12 add-absence-checkbox'
                ],
            ],
            [
                'name'  => 'add_absence',
                'label'    => __('backpack.incidences.add_absence'),
                'type'  => 'switch',
                'color'    => 'primary',
                'onLabel' => __('backpack.yes'),
                'offLabel' => __('backpack.no'),
            ],
            [
                'name'  => 'send',
                'label'    => __('backpack.incidences.send'),
                'type'  => 'switch',
                'color'    => 'primary',
                'onLabel' => __('backpack.yes'),
                'offLabel' => __('backpack.no'),
            ],
            [
                'name' => 'contact_email_id',
                'label' => __('backpack.incidences.contact_email_id'),
                'type' => 'select',
                'model' => 'App\Models\ContactEmail',
                'attribute' => 'showName',
                'entity' => 'contact_email',
                'wrapper' => [
                    'class' => 'form-group col-sm-12 d-none'
                ],
                'options'   => (function ($query) {
                    return $query->orderBy('name')->get();
                }),
            ],
            [
                'name' => 'script',
                'type' => 'view',
                'view' => 'admin/incidence/script'
            ]
        ]);

        if (!Cache::has('market' . auth()->user()->id)) {
            $this->crud->addField([
                'name'      => 'market_id',
                'label'     => __('backpack.stalls.market'), // Table column heading
                'type'      => 'select2',
                'entity'    => 'market',
                'attribute' => 'name',
                'model'     => "App\Models\Market",
                'options'   => (function ($query) {
                    return $query->whereIn('id', backpack_user()->my_markets->pluck('id')->toArray())->get();
                }),
            ])->afterField('type');
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

        $this->crud->removeField('stall_id');
        $this->crud->addField([
            'name'      => 'person_stall_name',
            'label'     => __('backpack.absences.person_id'),
            'type'      => 'text',
            'attributes' => [
                'disabled'  => 'disabled',
            ],
        ])->afterField('type');
        $this->crud->modifyField('type', [
            'attributes' => [
                'disabled'  => 'disabled',
            ],
        ]);
        $this->crud->modifyField('send', [
            'disabled'  => 'disabled',
        ]);
        $this->crud->modifyField('contact_email_id', [
            'attributes' => [
                'disabled'  => 'disabled',
            ],
        ]);
        $this->crud->addFields([
            [
                'name' => 'status',
                'label' => __('backpack.incidences.status'),
                'type' => 'select_from_array',
                'options' => [
                    'pending' => __('backpack.incidences.statuses.pending'),
                    'solved' => __('backpack.incidences.statuses.solved')
                ],
                'allows_null' => false,
            ],
        ]);
    }

    protected function showCalendarIncidence()
    {
        $this->crud->eventsCalendar = $this->crud->getEntries()->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'start' => $item->date_incidence
            ];
        })->toArray();
    }

    public function store()
    {
        $request = $this->crud->validateRequest();
        $stall_id = $request->get('stall_id');
        $date_incidence = Carbon::parse($request->get('date_incidence'));


        $canSotre = false;
        switch ($request->get('type')) {
            case 'owner_incidence':
            case 'specific_activities':
                $stall = Stall::find($stall_id);
                $titular = $stall ? $stall->getTitular() : false;
                // Comprobamos que el puesto tenga dueño, sino devolvemos error
                if (!$stall || !$titular) {
                    return Redirect::back()->withInput($request->all())->withErrors(['msg' => __('backpack.incidences.errors.no_titular')]);
                }

                // Comprovamos fecha
                if (!$date_incidence->between(Carbon::parse($titular->pivot->start_at),  Carbon::parse($titular->pivot->end_vigencia))) {
                    return Redirect::back()->withInput($request->all())->withErrors(['msg' => __('backpack.incidences.errors.date_between')]);
                }

                $market = $stall->market;
                // Comprovamos que el mercado tenga alguna fecha que coincida con la fecha de la incidencia, sino volvemos atras
                $canSotre = Calendar::where('market_id', $market->id)->where('date', $date_incidence)->first();
                break;
            case 'general_incidence':
                // Comprovamos que el mercado tenga alguna fecha que coincida con la fecha de la incidencia, sino volvemos atras
                $canSotre = Calendar::where('market_id',  $request->get('market_id', Cache::get('market' . auth()->user()->id)))->where('date', $date_incidence)->first();
                break;
        }

        if ($canSotre) {
            $response = $this->traitStore();
            // do something after save
            return $response;
        }

        return Redirect::back()
            ->withInput($request->all())
            ->withErrors(['msg' => __('backpack.incidences.errors.no_dates')]);
    }
}
