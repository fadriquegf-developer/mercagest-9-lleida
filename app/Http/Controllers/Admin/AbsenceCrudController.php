<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Requests\AbsenceRequest;
use App\Http\Requests\AbsenceRequestUpdate;
use App\Models\Absence;
use App\Models\Person;
use App\Models\Stall;
use App\Models\Calendar;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AbsenceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AbsenceCrudController extends CrudController
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
        $this->crud->setModel(\App\Models\Absence::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/absence');
        $this->crud->setEntityNameStrings(__('backpack.absences.single'), __('backpack.absences.plural'));
        $this->setPermissions('absences');
    }


    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause('ByMarketSelected');
        $this->setFilterToListByRequest();
        $this->crud->enableExportButtons();

        $this->crud->addColumns([
            ['name' => 'id', 'label' => __('backpack.absences.id')],
            [
                'name' => 'type',
                'label' => __('backpack.absences.type'),
                'type' => 'select_from_array',
                'options' => [
                    'justificada' => __('backpack.absences.types.justificada'),
                    'no-justificada' => __('backpack.absences.types.no-justificada')
                ]
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
            ['name' => 'start', 'label' => __('backpack.absences.start'), 'type' => 'date'],
            ['name' => 'end', 'label' => __('backpack.absences.end'), 'type' => 'date'],
            [
                'name' => 'document',
                'label' => __('backpack.absences.document'),
                'type'     => 'custom_html',
                'value' => function ($entry) {
                    $text = '-';
                    if ($entry->document) {
                        $text = '<a href="' . $entry->getDocumentUrl() . '" target="_blank">' . __('backpack.absences.show_doc') . '</a>';
                    }

                    return $text;
                }
            ],
        ]);

        /** Filters */
        $this->crud->addFilter([
            'name'  => 'type',
            'label' => __('backpack.absences.type'),
            'type'  => 'dropdown',
        ], [
            'justificada' => __('backpack.absences.types.justificada'),
            'no-justificada' => __('backpack.absences.types.no-justificada')
        ], function ($value) {
            $this->crud->addClause('where', 'type', $value);
        });
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AbsenceRequest::class);

        $this->crud->addFields([
            [
                'name' => 'type',
                'label' => __('backpack.absences.type'),
                'type' => 'select_from_array',
                'options' => [
                    'justificada' => __('backpack.absences.types.justificada'),
                    'no-justificada' => __('backpack.absences.types.no-justificada')
                ]
            ],
            [
                'name'      => 'stalls',
                'label'     => __('backpack.absences.person_id'),
                'type'      => 'select2_multiple',
                'entity'    => 'stall',
                'model'     => "App\Models\Stall",
                'attribute' => 'num_market_active_titular',
                'options'   => (function ($query) {
                    return $query->filterByMarket()->activeTitular()->get();
                }),
                'value' => [request()->get('stall_id') ?: '']
            ],
            [
                'name' => 'cause',
                'label' => __('backpack.absences.cause'),
                'type'  => 'textarea',
                'wrapper' => [
                    'class' => 'form-group col-12'
                ],
            ],
            [
                'name' => 'start',
                'type' => 'date',
                'label' => __('backpack.absences.start'),
                'default' => isset(request()->date) ? request()->date : '',
                'wrapper' => [
                    'class' => 'form-group col-12'
                ],
            ],
            [
                'name' => 'end',
                'type' => 'date',
                'label' => __('backpack.absences.end'),
                'default' => isset(request()->date) ? request()->date : '',
                'wrapper' => [
                    'class' => 'form-group col-12'
                ],
            ],
            [
                'name'      => 'document',
                'label'     => __('backpack.absences.document'),
                'type'      => 'upload',
                'upload'    => true,
                'prefix'    => config('backpack.base.route_prefix', 'admin') . '/storage/'
            ],
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
        // check if user is allowed to edit this record
        $this->checkUserAllowedMarkets($this->crud->getCurrentEntry()->stall->market_id);

        $this->setupCreateOperation();

        $this->crud->setValidation(AbsenceRequestUpdate::class);
        $this->crud->removeField('stalls');
        $this->crud->addField([
            'name'      => 'person_stall_name',
            'label'     => __('backpack.absences.person_id'),
            'type'      => 'text',
            'attributes' => [
                'disabled'  => 'disabled',
            ],
        ])->afterField('type');
    }

    protected function setupDeleteOperation()
    {
        // check if user is allowed to edit this record
        $this->checkUserAllowedMarkets($this->crud->getCurrentEntry()->stall->market_id);
    }

    protected function setFilterToListByRequest()
    {
        if (isset(request()->stall_id)) {
            $this->crud->addClause('filterByStall', request()->stall_id);
        }
    }

    public function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function store(AbsenceRequest $request)
    {
        $stalls = request()->stalls;

        //Creamos una absencia por cada puesto seleccionado
        foreach ($stalls as $stall) {
            $stall = Stall::find($stall);
            //Comprobamos que el puesto tenga dueño, sino devolvemos error
            if ($stall->historicals->count() == 0 || $stall->historicals->where('ends_at', '!=', NULL)->first()) {
                return Redirect::back()->withErrors(['msg' => __('backpack.incidences.errors.stall_no_titular', ['stall' => $stall->num_market ?? ''])]);
            }
            Absence::create([
                'person_id' => $stall->historicals()->where('ends_at', NULL)->first()->id,
                'stall_id' => $stall->id,
                'type' => $request->get('type'),
                'cause' => $request->get('cause'),
                'start' => $request->get('start'),
                'end' => $request->get('end'),
                'document' => $request->file('document')
            ]);
        }

        return redirect()->route('absence.index');
    }

    public function toggleAbcence(Request $request)
    {
        $calendar_id = $request->input('calendar_id');
        $stall_id = $request->input('stall');
        $owner_id = $request->input('owner');
        $value = $request->input('value');

        $day = Calendar::filterByMarket()->where('id', $calendar_id)->first();

        $response = false;
        if ($value === "true") {
            $response = true;
            Absence::where('stall_id', $stall_id)->byBusy($day->date, $day->date)->delete();
        } else {
            // create abcence
            Absence::create([
                'person_id' => $owner_id,
                'stall_id' => $stall_id,
                'type' => Absence::TYPE_ABSENT,
                'cause' => 'Absència generada des del mapa',
                'start' => $day->date,
                'end' => $day->date,
            ]);
        }

        return response()->json(['success' => $response]);
    }
}
