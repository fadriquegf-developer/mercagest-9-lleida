<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Prologue\Alerts\Facades\Alert;
use App\Http\Requests\PersonRequest;
use App\Imports\PersonsImport;
use App\Models\MarketGroup;
use App\Models\Person;

/**
 * Class PersonCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PersonCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
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
        $this->crud->setModel(\App\Models\Person::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/person');
        $this->crud->setEntityNameStrings(__('backpack.persons.single'), __('backpack.persons.plural'));
        $this->crud->setShowView('admin.person.show');
        $this->setPermissions('persons');
        $this->crud->addButtonFromModelFunction('line', 'expedientesButton', 'expedientesButton', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'accreditation', 'accreditation', 'beginning');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        if (!request()->has('unsubscribed')) {
            $this->crud->addClause('active');
        }
        if (!request()->has('active')) {
            $this->filterQueryActive();
        }
        $this->crud->enableExportButtons();

        $this->crud->addClause('ByMarketSelected');

        if (!$this->crud->getRequest()->has('order')) {
            $this->crud->addClause('orderByHistorical');
        }

        $this->crud->addColumns([
            ['name' => 'name', 'label' => __('backpack.persons.name')],
            [
                'name' => 'historicals',
                'label' => __('backpack.persons.historicals'),
                'type' => 'text',
                'escaped' => false,
                'limit' => 1000,
                'value' => function ($entry) {
                    $text = '';
                    $markets = $entry->historicals()->filterByMarket()->pivotActiveTitular()
                        ->with(['market', 'marketGroup'])->get()->groupBy('market_id');

                    $lastKey = $markets->keys()->last();
                    foreach ($markets as $key => $stalls) {
                        $text .= '<b>' . $stalls->first()->market->name;
                        $text .= '</b>:<br>';
                        $textStalls = collect();
                        foreach ($stalls as $stall) {
                            $marketTitle = $stall->marketGroup->title ?? '';
                            $textStalls->push("{$stall->num}({$marketTitle})");
                        }
                        $text .= ' ' . $textStalls->implode('<br>');

                        if ($key !== $lastKey) {
                            $text .= '<br><br>';
                        }
                    }
                    return  $text;
                }
            ],
            ['name' => 'license_number', 'label' => __('backpack.persons.num'), 'type' => 'text'],
            ['name' => 'dni', 'label' => __('backpack.persons.dni')],
            ['name' => 'email', 'label' => __('backpack.persons.email'), 'type' => 'email'],
            ['name' => 'phone', 'label' => __('backpack.persons.phone'), 'type' => 'phone'],
            ['name' => 'phone_2', 'label' => __('backpack.persons.phone') . ' 2', 'type' => 'phone'],
            ['name' => 'phone_3', 'label' => __('backpack.persons.phone') . ' 3', 'type' => 'phone'],
            ['name' => 'name_titular', 'label' => __('backpack.persons.name_titular')],
            ['name' => 'nif_titular', 'label' => __('backpack.persons.nif_titular')],
            ['name' => 'iban', 'label' => __('backpack.persons.iban')],
            ['name' => 'date_domiciliacion', 'label' => __('backpack.persons.date_domiciliacion')],
            ['name' => 'ref_domiciliacion', 'label' => __('backpack.persons.ref_domiciliacion')],
            ['name' => 'pdf1', 'label' => __('backpack.persons.pdf1')],
            ['name' => 'pdf2', 'label' => __('backpack.persons.pdf2')],
            ['name' => 'address', 'label' => __('backpack.persons.address')],
            ['name' => 'city', 'label' => __('backpack.persons.city')],
            ['name' => 'zip', 'label' => __('backpack.persons.zip')],
            ['name' => 'region', 'label' => __('backpack.persons.region')],
            ['name' => 'province', 'label' => __('backpack.persons.province')],
            ['name' => 'type_address', 'label' => __('backpack.persons.type_address')],
            ['name' => 'number_address', 'label' => __('backpack.persons.number_address')],
            ['name' => 'extra_address', 'label' => __('backpack.persons.extra_address')],
            [
                'name'  => 'substitutes',
                'label' => __('backpack.persons.substitutes'),
                'type'  => 'model_function',
                'function_name' => 'getSubstitutesName',
            ]
        ]);

        $this->crud->addFilter([
            'name'  => 'unsubscribed',
            'type'  => 'dropdown',
            'label' => __('backpack.persons.filter_unsubscribe.title')
        ], [
            'inactive' => __('backpack.persons.filter_unsubscribe.inactive'),
            'active' => __('backpack.persons.filter_unsubscribe.active'),
        ], function ($value) {
            if ($value === 'active') {
                $this->crud->addClause('active');
            } else {
                $this->crud->addClause('unsubscribed');
            }
        });

        $this->crud->addFilter([
            'name'  => 'active',
            'type'  => 'dropdown',
            'label' => __('backpack.persons.filter_active.title')
        ], [
            'active' => __('backpack.persons.filter_active.active'),
            'inactive' => __('backpack.persons.filter_active.inactive'),
        ], function ($value) {
            if ($value === 'active') {
                $this->filterQueryActive();
            } else if ($value === 'inactive') {
                // the filter is by default on this option dissable the filter
            }
        });

        $this->crud->addFilter([
            'name'  => 'market_group_id',
            'type'  => 'dropdown',
            'label' => __('backpack.stalls.market_group_id'),
        ], function () {
            return MarketGroup::active()->get()->pluck('title', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('whereHas', 'historicals', function ($query) use ($value) {
                $query->pivotActiveTitular()->where('market_group_id', $value);
            });
        });

        $this->crud->addButtonFromModelFunction('line', 'legal_doc2', 'btnLegalDoc2', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'legal_doc1', 'btnLegalDoc1', 'beginning');
        $this->crud->addButtonFromView('line', 'unsubscribe', 'toggle_subscribe', 'beginning');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PersonRequest::class);

        $uploadsPrefix = url(config('backpack.base.route_prefix', 'admin')) . '/storage/';

        $contactInfo = __('backpack.persons.contact_info');
        $substitutes = __('backpack.persons.substitutes');
        $docs = __('backpack.persons.docs');

        $this->crud->addFields([
            ['name' => 'license_number', 'label' => __('backpack.persons.num'), 'type' => 'text'],
            ['name' => 'name', 'label' => __('backpack.persons.name')],
            ['name' => 'email', 'type' => 'email', 'label' => __('backpack.persons.email')],
            ['name' => 'dni', 'label' => __('backpack.persons.dni')],
            ['name' => 'phone', 'label' => __('backpack.persons.phone')],
            [
                'name' => 'image',
                'label' => __('backpack.persons.image'),
                'type' => 'upload_preview',
                'upload' => true,
                'prefix' => $uploadsPrefix
            ],

            // contact
            [
                'name' => 'type_address',
                'label' => __('backpack.persons.type_address'),
                'type'        => 'select_from_array',
                'options'     => ['CA' => 'Carrer', 'AV' => 'Avinguda', 'UR' => 'Urbanització', 'PL' => 'Plaça'],
                'allows_null' => false,
                'default'     => 'ca',
                'tab' => $contactInfo
            ],
            [
                'name' => 'address',
                'label' => __('backpack.persons.address'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'number_address',
                'label' => __('backpack.persons.number_address'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'extra_address',
                'label' => __('backpack.persons.extra_address'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'city',
                'label' => __('backpack.persons.city'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'zip',
                'label' => __('backpack.persons.zip'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'region',
                'label' => __('backpack.persons.region'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'province',
                'label' => __('backpack.persons.province'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'phone_2',
                'label' => __('backpack.persons.phone') . ' 2',
                'tab' => $contactInfo
            ],
            [
                'name' => 'phone_3',
                'label' => __('backpack.persons.phone') . ' 3',
                'tab' => $contactInfo
            ],
            [
                'name' => 'name_titular',
                'label' => __('backpack.persons.name_titular'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'nif_titular',
                'label' => __('backpack.persons.nif_titular'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'iban',
                'label' => __('backpack.persons.iban'),
                'tab' => $contactInfo
            ],
            [
                'name' => 'ref_domiciliacion',
                'label' => __('backpack.persons.ref_domiciliacion'),
                'attributes' => [
                    'readonly' => 'readonly',
                ],
                'tab' => $contactInfo
            ],
            [
                'name' => 'date_domiciliacion',
                'label' => __('backpack.persons.date_domiciliacion'),
                'type' => 'date',
                'tab' => $contactInfo
            ],
            // [
            //     'name' => 'pdf1',
            //     'label' => __('backpack.persons.pdf1'),
            //     'type' => 'upload',
            //     'upload' => true,
            //     'prefix' => $uploadsPrefix,
            //     'tab' => $contactInfo
            // ],
            // [
            //     'name' => 'pdf2',
            //     'label' => __('backpack.persons.pdf2'),
            //     'type' => 'upload',
            //     'upload' => true,
            //     'prefix' => $uploadsPrefix,
            //     'tab' => $contactInfo
            // ],
            // [
            //     'name' => 'legal_doc3',
            //     'label' => __('backpack.persons.legal_doc3'),
            //     'type' => 'upload',
            //     'upload' => true,
            //     'prefix' => $uploadsPrefix,
            //     'tab' => $contactInfo
            // ],

            [
                'name' => 'substitute1_name',
                'label' => __('backpack.persons.substitute_name') . ' 1',
                'tab' => $substitutes
            ],
            [
                'name' => 'substitute1_img',
                'label' => __('backpack.persons.substitute_img') . ' 1',
                'type' => 'upload',
                'upload' => true,
                'prefix' => $uploadsPrefix,
                'tab' => $substitutes
            ],
            [
                'name' => 'substitute1_dni',
                'label' => __('backpack.persons.substitute_dni') . ' 1',
                'tab' => $substitutes
            ],
            [
                'name' => 'substitute1_dni_img',
                'label' => __('backpack.persons.substitute_dni_img') . ' 1',
                'type' => 'upload',
                'upload' => true,
                'prefix' => $uploadsPrefix,
                'tab' => $substitutes
            ],
            [
                'name' => 'substitute2_name',
                'label' => __('backpack.persons.substitute_name') . ' 2',
                'tab' => $substitutes
            ],
            [
                'name' => 'substitute2_img',
                'label' => __('backpack.persons.substitute_img') . ' 2',
                'type' => 'upload',
                'upload' => true,
                'prefix' => $uploadsPrefix,
                'tab' => $substitutes
            ],
            [
                'name' => 'substitute2_dni',
                'label' => __('backpack.persons.substitute_dni') . ' 2',
                'tab' => $substitutes
            ],
            [
                'name' => 'substitute2_dni_img',
                'label' => __('backpack.persons.substitute_dni_img') . ' 2',
                'type' => 'upload',
                'upload' => true,
                'prefix' => $uploadsPrefix,
                'tab' => $substitutes
            ],
            // [ // temp old sistem
            //     'label' => __('backpack.persons.substitutes'),
            //     'type' => 'select2_multiple',
            //     'name' => 'substitutes', // the db column for the foreign key
            //     'entity' => 'substitutes', // the method that defines the relationship in your Model
            //     'model' => "App\Models\Person", // foreign key model
            //     'attribute' => 'name', // foreign key attribute that is shown to user
            //     'pivot' => true,
            //     'options' => (function ($query) {
            //         return $query->orderBy('name', 'ASC')->where('id', '!=', request()->route()->id)->get();
            //     }),
            //     'attributes' => [
            //         'disabled' => true,
            //     ],
            //     'tab' => $substitutes,
            //     'hint' => 'Sistema antic d\'acompanyants'
            // ],

            [
                'name' => 'docs',
                'type' => "relationship",
                'tab' => $docs,

                'subfields'   => [
                    [
                        'name' => 'type',
                        'label' => __('backpack.persons.type'),
                        'type' => 'text',
                        'wrapper' => [
                            'class' => 'form-group col-md-3',
                        ],
                    ],
                    [
                        'name' => 'doc',
                        'label' => __('backpack.persons.doc'),
                        'type' => 'upload',
                        'upload' => true,
                        'prefix' => $uploadsPrefix,
                        'wrapper' => [
                            'class' => 'form-group col-md-9',
                        ],
                    ],
                ],
            ],

            [
                'name' => 'comment',
                'label' => __('backpack.persons.comment'),
                'type' => 'textarea',
                'tab' => __('backpack.persons.comment')
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

    protected function setupShowOperation()
    {
        $this->crud->addColumns([
            ['name' => 'name', 'label' => __('backpack.persons.name')],
            ['name' => 'email', 'label' => __('backpack.persons.email')],
            ['name' => 'dni', 'label' => __('backpack.persons.dni')],
            ['name' => 'type_address', 'label' => __('backpack.persons.type_address')],
            ['name' => 'address', 'label' => __('backpack.persons.address')],
            ['name' => 'number_address', 'label' => __('backpack.persons.number_address')],
            ['name' => 'extra_address', 'label' => __('backpack.persons.extra_address')],
            ['name' => 'city', 'label' => __('backpack.persons.city')],
            ['name' => 'zip', 'label' => __('backpack.persons.zip')],
            ['name' => 'region', 'label' => __('backpack.persons.region')],
            ['name' => 'province', 'label' => __('backpack.persons.province')],
            ['name' => 'phone', 'label' => __('backpack.persons.phone')],
            ['name' => 'phone_2', 'label' => __('backpack.persons.phone') . ' 2'],
            ['name' => 'phone_3', 'label' => __('backpack.persons.phone') . ' 3'],
            ['name' => 'iban', 'label' => __('backpack.persons.iban')],
            [
                'name' => 'pdf1',
                'label' => __('backpack.persons.pdf1'),
                'type'     => 'custom_html',
                'value' => function ($entry) {
                    $text = '-';
                    if ($entry->pdf1) {
                        $text = '<a href="' . $entry->pdf1Url . '" target="_blank">' . __('backpack.absences.show_doc') . '</a>';
                    }

                    return $text;
                }
            ],
            [
                'name' => 'pdf2',
                'label' => __('backpack.persons.pdf2'),
                'type'     => 'custom_html',
                'value' => function ($entry) {
                    $text = '-';
                    if ($entry->pdf2) {
                        $text = '<a href="' . $entry->pdf2Url . '" target="_blank">' . __('backpack.absences.show_doc') . '</a>';
                    }

                    return $text;
                }
            ],
            [
                'name'  => 'substitutes',
                'label' => __('backpack.persons.substitutes'),
                'type'  => 'model_function',
                'function_name' => 'getSubstitutesName',
            ],
            [
                'name' => 'comment',
                'label' => __('backpack.persons.comment'),
                'type' => 'textarea',
            ]
        ]);
    }

    public function store()
    {
        $substitutes = $this->crud->getRequest()->substitutes;
        $this->crud->getRequest()->request->remove('substitutes');
        $response = $this->traitStore();
        $this->crud->entry->substitutes()->attach(collect($substitutes)->whereNotNull()->toArray());
        return $response;
    }

    public function update()
    {
        $substitutes = $this->crud->getRequest()->substitutes;
        $this->crud->getRequest()->request->remove('substitutes');
        $response = $this->traitUpdate();
        $this->crud->entry->substitutes()->sync(collect($substitutes)->whereNotNull()->toArray());
        return $response;
    }

    public function getAccreditation($id)
    {
        $person = Person::findOrFail($id);
        $pdf = \App\Services\PersonService::getAccreditation($person);
        if (request()->input('view')) {
            return $pdf;
        }

        return $pdf->stream();
    }

    public function ajaxSearch(Request $request)
    {
        $search_term = $request->input('q');

        if ($search_term) {
            $results = Person::where(function ($query) use ($search_term) {
                $query->where('name', 'LIKE', '%' . $search_term . '%')
                    ->orWhereHas('historicals', function ($query) use ($search_term) {
                        $query->where('num', 'LIKE', '%' . $search_term . '%');
                    });
            })->with('historicals')->paginate(10);
        } else {
            $results = Person::paginate(10);
        }

        // repeatable 
        if ($request->has('keys')) {
            return Person::findMany($request->input('keys'));
        }

        return $results;
    }

    public function toggleSubscribe(Request $request, $id)
    {
        $person = Person::find($id);

        if ($person->unsubscribe_date) {
            $person->unsubscribe_date = null;
            $message = __('backpack.persons.restore_success');
        } else {
            \Alert::success(__('backpack.persons.unsubscribe_success'))->flash();
            $person->unsubscribe_date = \Carbon\Carbon::now();
            $message = __('backpack.persons.unsubscribe_success');
        }
        $person->save();

        if ($request->ajax()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->route('person.index');
    }

    private function filterQueryActive()
    {
        $this->crud->query->whereHas('historicals', function ($query) {
            $marketId = \Cache::get('market' . auth()->user()->id);
            if ($marketId) {
                $ids = [$marketId];
            } else {
                $ids = backpack_user()->my_markets->pluck('id')->toArray();
            }

            $query->pivotActiveTitular()->whereIn('market_id', $ids);
        });
    }

    public function importExtra()
    {
        Excel::import(new PersonsImport, public_path('/DomicBancariesJavajan.xls'), null, \Maatwebsite\Excel\Excel::XLS);

        \Alert::success('Import de informacio completat')->flash();

        return redirect()->route('person.index');
    }

    public function migrateSubstitutes()
    {
        // disabled
        abort(403);
        $persons = Person::has('substitutes')->with('substitutes')->get();
        $basePath = app('tenant')->name . "/person";

        foreach ($persons as $person) {
            $substitutes = clone $person->substitutes;

            $first = $substitutes->shift();
            if ($first) {
                $person->substitute1_name = $first->name;
                $person->substitute1_dni = $first->dni;

                // if need clone image
                if ($first->image) {
                    $name = md5(pathinfo($first->image, PATHINFO_FILENAME) . random_int(1, 9999) . time());
                    $extencion = pathinfo($first->image, PATHINFO_EXTENSION);
                    $path = $basePath . "/{$name}.{$extencion}";
                    \Storage::disk('local')->copy($first->image, $path);
                    $person->substitute1_img = $path;
                }
            }

            $second = $substitutes->shift();
            if ($second) {
                $person->substitute2_name = $second->name;
                $person->substitute2_dni = $second->dni;

                // if need clone image
                if ($second->image) {
                    $name = md5(pathinfo($second->image, PATHINFO_FILENAME) . random_int(1, 9999) . time());
                    $extencion = pathinfo($second->image, PATHINFO_EXTENSION);
                    $path = $basePath . "/{$name}.{$extencion}";
                    \Storage::disk('local')->copy($second->image, $path);
                    $person->substitute2_img = $path;
                }
            }
            $person->save();
        }
        dd('Migració realitzada.');
    }

    public function showLegalDoc($id, $type)
    {
        $this->checkValidTypes($type);
        $person = Person::findOrFail($id);
        $crud = $this->crud;

        if ($person->{"legal_doc{$type}_signature_date"}) {
            // show doc
            $folder = $person->getFolderDocs();
            return response()->file(Storage::disk('local')->path($person->{"legal_doc{$type}"}));
        }

        return view('admin.person.legal' . $type, compact('person', 'crud', 'type'));
    }

    public function storeLegalDoc(Request $request, $id, $type)
    {
        $this->checkValidTypes($type);

        // check if can save
        $person = Person::findOrFail($id);
        $folder = $person->getFolderDocs();

        if ($person->{"legal_doc{$type}_signature_date"}) {
            return response()->file(Storage::disk('local')->path($person->{"legal_doc{$type}"}));
        }

        $rules = [
            'signature' => 'required',
            'act' => 'required',
            'myself_nif' => 'required_if:act,myself',
            'myself_name' => 'required_if:act,myself',
            'other_nif' => 'required_if:act,other',
            'other_name' => 'required_if:act,other',
            'accept' => 'required',
            'signature_date' => 'required'
        ];

        switch ($type) {
            case 1:
                $rules['national_insurance'] = 'required';
                break;
            case 2:
                $rules['tax_administration'] = 'required';
                break;
        }

        $niceNames = [
            'signature' => 'Signatura',
            'act' => 'Actua',
            'act.myself' => 'Persona interessada',
            'myself_nif' => 'NIF persona interessada',
            'myself_name' => 'Nom persona interessada',
            'other_nif' => 'NIF persona representant',
            'other_name' => 'Nom persona representant',
            'accept' => 'He llegit i accepto',
            'national_insurance' => 'Tresoreria General de la Seguretat Social',
            'tax_administration' => 'Agència Estatal d\'Administració Tributària'
        ];

        $data = $request->all();
        // auto completed and not editable
        $data['myself_nif'] = $person->dni;
        $data['myself_name'] = $person->name;

        $validated = $request->validate($rules, [], $niceNames);

        $signature = $request->input('signature');

        // create folder 
        $sotrage = Storage::disk('local');
        $sotrage->makeDirectory($folder);

        // create full path
        $path = $folder . "legal_{$type}_{$person->id}.pdf";
        $person->{"legal_doc{$type}"} = $path;
        $filePath = $sotrage->path($path);

        // save date
        $person->{"legal_doc{$type}_signature_date"} = now();
        $person->{"legal_doc{$type}_user"} = backpack_user()->id;
        $person->save();

        // Create PDF
        $pdf = PDF::loadView('admin.person.pdf_legal' . $type,  compact('person', 'signature', 'data'));

        // save file to storage
        $pdf->save($filePath);

        return redirect()->route('person.legal_doc', ['id' => $id, 'type' => $type]);
    }

    public function viewCheckAccreditation()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;


        $this->crud->addFields([
            [
                'name' => 'license_number',
                'label' => __('backpack.persons.num'),
                'type' => 'text'
            ],
        ]);

        return view('admin.person.check_accreditation', $this->data);
    }

    public function checkAccreditation(Request $request)
    {
        $this->crud->hasAccessOrFail('list');

        $request->validate(['license_number' => 'required']);

        $person = Person::where('license_number', $request->input('license_number'))->first();

        if ($person) {
            // redirect to accreditation
            return redirect()->route('person.accreditation', ['id' => $person->id]);
        } else {
            // redirect to check
            Alert::warning(trans('backpack.persons.errors.accreditation_404'))->flash();
            return redirect()->route('person.check_accreditation.form');
        }
    }

    private function checkValidTypes($type)
    {
        $validTypes = [1, 2];
        if (!in_array($type, $validTypes)) {
            abort(403, "Invalid doc type");
        }
    }
}
