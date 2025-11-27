<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StallRequest;
use App\Imports\StallsImport;
use App\Models\Historical;
use App\Models\MarketGroup;
use App\Models\Stall;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class StallCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StallCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\AdminPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Stall::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/stall');
        $this->crud->setEntityNameStrings(__('backpack.stalls.single'), __('backpack.stalls.plural'));
        $this->crud->setShowView('admin.stall.show');
        $this->crud->setListView('admin.stall.list');
        $this->setPermissions('stalls');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        if (!request()->has('visible')) {
            $this->crud->addClause('visible');
        }

        $this->crud->enableExportButtons();
        $this->crud->addClause('filterByMarket');

        /* $this->crud->addButtonFromModelFunction('line', 'concession', 'concession', 'beginning'); */
        // $this->crud->addButtonFromModelFunction('line', 'checklist', 'createChecklist', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'accreditation', 'accreditation', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'certification', 'certification', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'unsubscribe', 'unsubscribe', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'subscribe', 'subscribe', 'beginning');

        $this->crud->addColumns([
            ['name' => 'num', 'label' => __('backpack.stalls.num')],
            [
                'name' => 'titular',
                'label' => __('backpack.stalls.titular'),
                'type'  => 'model_function',
                'function_name' => 'activeTitularLink',
                'escaped' => false,
                'limit' => 500,
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('historicals', function ($query) use ($column, $searchTerm) {
                        $query->pivotActiveTitular()->where('name', 'like', '%' . $searchTerm . '%');
                    });
                },
            ],
            ['name' => 'length', 'label' => __('backpack.stalls.length')],
            [
                'label'     => __('backpack.stalls.market'), // Table column heading
                'type'      => 'select',
                'name'      => 'market_id',
                'entity'    => 'market',
                'attribute' => 'name',
                'model'     => "App\Models\Market",
            ],
            [
                'label' =>  __('backpack.stalls.market_group_id'),
                'type' => 'select',
                'name' => 'market_group_id',
                'entity' => 'marketGroup',
                'attribute' => 'title',
                'model' => "App\Models\MarketGroup",
            ],
            [
                'label' =>  __('backpack.stalls.auth_prod_id'),
                'type' => 'select_multiple',
                'name' => 'auth_prods',
                'entity' => 'auth_prods',
                'attribute' => 'name',
                'model' => "App\Models\AuthProd",
            ],
            [
                'label' =>  __('backpack.stalls.classe_id'),
                'type' => 'select',
                'name' => 'classe_id',
                'entity' => 'classe',
                'attribute' => 'name',
                'model' => "App\Models\Classe",
            ],
            [
                'name'  => 'active',
                'label' => __('backpack.stalls.active'),
                'type'  => 'model_function',
                'function_name' => 'getIfActive',
                'wrapper' => [
                    'element' => 'span',
                    'class' => function ($crud, $column, $entry, $related_key) {
                        return $entry->hasActiveTitular() ? 'badge badge-success p-2' : 'badge badge-danger p-2';
                    },
                ]
            ],
            [
                'name' => 'from',
                'label' => __('backpack.stalls.from'),
                'type'  => 'date',
            ],
            [
                'name' => 'to',
                'label' => __('backpack.stalls.to'),
                'type'  => 'date',
            ]
        ]);

        $this->crud->addFilter([
            'name'  => 'status',
            'type'  => 'dropdown',
            'label' => __('backpack.stalls.active')
        ], [
            'active' => __('backpack.yes'),
            'inactive' => __('backpack.no'),
        ], function ($value) {
            if ($value === 'active') {
                $this->crud->addClause('ActiveTitular');
            } else if ($value == 'inactive') {
                $this->crud->addClause('NoActiveTitular');
            }
        });

        $this->crud->addFilter([
            'name'  => 'visible',
            'type'  => 'dropdown',
            'label' => __('backpack.stalls.visible')
        ], [
            'active' => __('backpack.yes'),
            'inactive' => __('backpack.no'),
        ], function ($value) {
            if ($value === 'active') {
                $this->crud->addClause('visible');
            } else if ($value == 'inactive') {
                $this->crud->addClause('visible', false);
            }
        });

        $this->crud->addFilter([
            'name'  => 'market_group_id',
            'type'  => 'dropdown',
            'label' => __('backpack.stalls.market_group_id'),
        ], function () {
            return MarketGroup::active()->get()->pluck('title', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'market_group_id', $value);
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
        $this->crud->setValidation(StallRequest::class);

        $this->crud->addFields([
            [
                'name'  => 'active_titular',
                'type'     => 'stall_active_titular',
            ],
            [
                'name'  => 'visible',
                'type'  => 'switch',
                'label'    => 'Oculta/Visible',
                'color'    => 'primary',
                'onLabel' => '✓',
                'offLabel' => '✕',
                'default' => true
            ],
            ['name' => 'num', 'label' => __('backpack.stalls.num')],
            [
                'name' => 'market_id',
                'label' => __('backpack.stalls.market_id'),
                'type' => 'select',
                'model' => 'App\Models\Market',
                'attribute' => 'name',
                'entity' => 'market',
                'allows_null_value' => __('backpack.stalls.marcket_default'),
                'allows_null' => true,
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('status', 1)->whereIn('id', backpack_user()->my_markets->pluck('id')->toArray())->get();
                }),
            ],
            [
                'name' => 'market_group_id',
                'label' => __('backpack.stalls.market_group'),
                'type' => 'select',
                'model' => 'App\Models\MarketGroup',
                'attribute' => 'title',
                'entity' => 'marketGroup',
                'allows_null_value' => __('backpack.stalls.market_group_default'),
                'allows_null' => true,
                'options'   => (function ($query) {
                    return $query->active()->get();
                }),
            ],
            [
                'name' => 'classe_id',
                'label' => __('backpack.stalls.classe_id'),
                'type' => 'select',
                'model' => 'App\Models\Classe',
                'attribute' => 'name',
                'entity' => 'classe',
                'allows_null_value' => __('backpack.stalls.classe_default'),
                'allows_null' => true,
            ],
            [
                'name'    => 'type',
                'label'   => __('backpack.stalls.type'),
                'type'    => 'select_from_array',
                'options' => [
                    Stall::TYPE_CONCESSION => __('backpack.stalls.types.concession'),
                    Stall::TYPE_RENT => __('backpack.stalls.types.rent'),
                ],
            ],
            [
                'name' => 'auth_prods',
                'label' => __('backpack.stalls.auth_prod_id'),
                'type' => 'select2_multiple',
                'model' => 'App\Models\AuthProd',
                'attribute' => 'name',
                'entity' => 'auth_prods',
                'allows_null_value' => __('backpack.stalls.auth_prod_default'),
                'allows_null' => true,
            ],
            [
                'label' => __('backpack.stalls.rate_id'),
                'type' => 'select',
                'name' => 'rate_id',
                'entity' => 'rate',
                'model' => "App\Models\Rate",
                'attribute' => 'name',
                'allows_null_value' => __('backpack.markets.rates_default'),
                'allows_null' => true,
                'hint' => __('backpack.stalls.rate_hint')
            ],
            [
                'name' => 'length',
                'label' => __('backpack.stalls.length'),
                'type' => 'number',
                'attributes' => ["step" => "any"]
            ],
            [
                'name' => 'image',
                'label' => __('backpack.stalls.image'),
                'type' => 'upload_preview',
                'upload' => true,
                'prefix' => url(config('backpack.base.route_prefix', 'admin')) . '/storage/'
            ],
            [
                'name' => 'comment',
                'label' => __('backpack.stalls.comment'),
                'type' => 'textarea'
            ],
            [   // CustomHTML
                'name'  => 'separator',
                'type'  => 'custom_html',
                'value' => '<label>Trimestres de Pagament</label>'
            ],
            [   // Checkbox
                'name'  => 'trimestral_1',
                'label' => 'Trimestre 1',
                'type'  => 'checkbox',
                'wrapper' => [
                    'class' => 'group-form col-md-3',
                ]
            ],
            [   // Checkbox
                'name'  => 'trimestral_2',
                'label' => 'Trimestre 2',
                'type'  => 'checkbox',
                'wrapper' => [
                    'class' => 'group-form col-md-3',
                ]
            ],
            [   // Checkbox
                'name'  => 'trimestral_3',
                'label' => 'Trimestre 3',
                'type'  => 'checkbox',
                'wrapper' => [
                    'class' => 'group-form col-md-3',
                ]
            ],
            [   // Checkbox
                'name'  => 'trimestral_4',
                'label' => 'Trimestre 4',
                'type'  => 'checkbox',
                'wrapper' => [
                    'class' => 'group-form col-md-3',
                ]
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
        $this->checkUserAllowedMarkets($this->crud->getCurrentEntry()->market_id);

        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        // check if user is allowed to edit this record
        $this->checkUserAllowedMarkets($this->crud->getCurrentEntry()->market_id);

        $this->crud->addColumns([
            ['name' => 'id', 'label' => __('backpack.stalls.id')],
            ['name' => 'num', 'label' => __('backpack.stalls.num')],
            ['name' => 'titular', 'label' => __('backpack.stalls.titular')],
            [
                'label'     => __('backpack.stalls.market'), // Table column heading
                'type'      => 'select',
                'name'      => 'market_id',
                'entity'    => 'market',
                'attribute' => 'name',
                'model'     => "App\Models\Market",
            ],
            [
                'label' =>  __('backpack.stalls.market_group_id'),
                'type' => 'select',
                'name' => 'market_group_id',
                'entity' => 'marketGroup',
                'attribute' => 'title',
                'model' => "App\Models\MarketGroup",
            ],
            [
                'label' =>  __('backpack.stalls.auth_prod_id'),
                'type' => 'select_multiple',
                'name' => 'auth_prods',
                'entity' => 'auth_prods',
                'attribute' => 'name',
                'model' => "App\Models\AuthProd",
            ],
            [
                'label' =>  __('backpack.stalls.classe_id'),
                'type' => 'select',
                'name' => 'classe_id',
                'entity' => 'classe',
                'attribute' => 'name',
                'model' => "App\Models\Classe",
            ],
            [
                'name'    => 'type',
                'label'   => __('backpack.stalls.type'),
                'type'    => 'select_from_array',
                'options' => [
                    Stall::TYPE_CONCESSION => __('backpack.stalls.types.concession'),
                    Stall::TYPE_RENT => __('backpack.stalls.types.rent'),
                ],
            ],
            [
                'label' =>  __('backpack.stalls.rate_id'),
                'type' => 'select',
                'name' => 'rate_id',
                'entity' => 'rate',
                'attribute' => 'name',
                'model' => "App\Models\Rate",
            ],
            ['name' => 'length', 'label' => __('backpack.stalls.length')],
            [
                'name'  => 'active',
                'label' => __('backpack.stalls.active'),
                'type'  => 'model_function',
                'function_name' => 'getIfActive',
            ],
            [
                'name' => 'comment',
                'label' => __('backpack.stalls.comment'),
                'type' => 'textarea'
            ]
        ]);
    }

    protected function setupDeleteOperation()
    {
        // check if user is allowed to edit this record
        $this->checkUserAllowedMarkets($this->crud->getCurrentEntry()->market_id);
    }

    public function getAccreditation($id)
    {
        // redirec to person accreditation
        $stall = Stall::find($id);
        $pdf = PDF::loadView('tenant.' . app()->tenant->name . '.stall.pdf_accreditation', compact('stall'));
        return $pdf->stream();
    }

    public function unsubscribeTitular($id)
    {
        $request = request();
        $stall = Stall::find($id);

        if ($stall) {
            $titular = $stall->getTitular();
            if ($titular) {
                // check end date
                $end = Carbon::parse($request->ends_at);
                if (!$end->between(Carbon::parse($titular->pivot->start_at),  Carbon::parse($titular->pivot->end_vigencia))) {
                    return response()->json(['error' => __('backpack.stalls.errors.end_date')], 422);
                }

                // unsubscribe
                $stall->unsubscribeTitular(request());
                return response()->json(['message' => __('backpack.stalls.unsubscribe_message')]);
            }
        }

        // error
        return response()->json(['error' => __('backpack.stalls.errors.no_stall')], 422);
    }

    public function subscribeTitular($id)
    {
        $start = Carbon::parse(request()->start_at);
        $end = Carbon::parse(request()->end_vigencia);

        if ($start->greaterThan($end)) {
            return response()->json(['error' => __('backpack.stalls.errors.after_start')], 422);
        }

        $existHistorical = Historical::where('stall_id', $id)->where(function ($query) use ($start, $end) {
            $query->where(function ($query) use ($start, $end) {
                $query->whereNull('ends_at')
                    ->where('end_vigencia', '!=', $start)
                    ->where(function ($query) use ($start, $end) {
                        $query->whereBetween('start_at', [$start, $end])
                            ->orWhereBetween('end_vigencia', [$start, $end])
                            ->orWhereRaw('? BETWEEN start_at and end_vigencia', [$start])
                            ->orWhereRaw('? BETWEEN start_at and end_vigencia', [$end]);
                    });
            })->orWhere(function ($query) use ($start, $end) {
                $query->whereNotNull('ends_at')
                    ->where('ends_at', '!=', $start)
                    ->where(function ($query) use ($start, $end) {
                        $query->whereBetween('start_at', [$start, $end])
                            ->orWhereBetween('ends_at', [$start, $end])
                            ->orWhereRaw('? BETWEEN start_at and ends_at', [$start])
                            ->orWhereRaw('? BETWEEN start_at and ends_at', [$end]);
                    });
            });
        })->count();

        if ($existHistorical) {
            // error overlap
            return response()->json(['error' => __('backpack.stalls.errors.overlap_dates')], 422);
        }

        Historical::create([
            'stall_id' => $id,
            'person_id' => request()->person_id,
            'start_at' => request()->start_at ?? Carbon::now()->toDateString(),
            'end_vigencia' => request()->end_vigencia,
            'family_transfer' => request()->family_transfer
        ]);

        return response()->json(['message' => __('backpack.stalls.subscribe_message')]);
    }

    public function getCertification($id)
    {
        $stall = Stall::find($id);
        if ($stall) {
            $data = $this->printCertificationParameters($stall);
            $pdf = PDF::loadView('tenant.' . app()->tenant->name . '.stall.pdf_certification', compact('data'));
            return $pdf->stream();
        }
        return redirect()->back();
    }

    private function printCertificationParameters($stall)
    {
        return [
            'market' => $stall->market->name,
            'titular' => $stall->titular,
            'stall' => $stall->num,
            'created_at' => Carbon::now()->format('d-m-Y')
        ];
    }

    public function getByMarket()
    {
        return Stall::filterByMarket()->pluck('num', 'id');
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        $entry = $this->crud->getCurrentEntry();
        if ($entry->historicals()->count() || $entry->absences()->count() || $entry->incidences()->count()) {
            return response([
                'error' => [__('backpack.stalls.cant_delete')],
            ], '200');
        }

        return $this->crud->delete($id);
    }

    /**
     * chache visibility of all inactive stalls to false
     */
    public function hiddeAllInactiveStalls()
    {
        $stalls = Stall::NoActiveTitular()->get();
        foreach ($stalls as $stall) {
            $stall->visible = false;
            $stall->save();
        }
        return 'ok';
    }

    public function importMeters()
    {
        Excel::import(new StallsImport, public_path('/parades_meters.xls'), null, \Maatwebsite\Excel\Excel::XLS);

        \Alert::success('Import de informacio completat')->flash();

        return redirect()->route('stall.index');
    }
}
