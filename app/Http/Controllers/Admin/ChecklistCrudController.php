<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\ChecklistAnswer;
use App\Models\ChecklistQuestion;
use App\Models\ChecklistType;
use App\Models\Market;
use App\Models\Stall;

/**
 * Class ChecklistCrudController 
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChecklistCrudController  extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Http\Controllers\Admin\Operations\SendOperation;
    use \App\Traits\AdminPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Checklist::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/checklist');
        $this->crud->setEntityNameStrings(__('backpack.checklists.single'), __('backpack.checklists.plural'));
        $this->setPermissions('checklists');
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
                'name' => 'checklist_type_id',
                'label' => __('backpack.checklists.single'),
                'type' => 'select',
                'entity' => 'checklist_type',
                'attribute' => 'name',
                'model' => ChecklistType::class,
            ],
            [
                'name' => 'origin_type',
                'label' => __('backpack.checklists.type'),
                'value' => function ($entry) {
                    $text = '';

                    if ($entry->origin_type === Stall::class) {
                        $text = __('backpack.checklists.types.stall');
                    } else if ($entry->origin_type === Market::class) {
                        $text = __('backpack.checklists.types.market');
                    }

                    return $text;
                }
            ],
            [
                'name' => 'origin_id',
                'label' => __('backpack.checklists.origin'),
                'type' => 'text',
                'value' => function ($entry) {
                    $text = '';

                    if ($entry->origin_type === Stall::class) {
                        $text = $entry->origin->num_market;
                    } else if ($entry->origin_type === Market::class) {
                        $text = $entry->origin->name;
                    }

                    return $text;
                }
            ],
            [
                'name' => 'created_at',
                'label' => __('backpack.checklists.created_at'),
                'type' => 'date',
            ],
        ]);

        $this->crud->addFilter([
            'name'  => 'origin_type',
            'type'  => 'dropdown',
            'label' => __('backpack.checklists.type')
        ], [
            'stall' => __('backpack.checklists.types.stall'),
            'market' => __('backpack.checklists.types.market'),
        ], function ($value) {
            $type = '';
            if ($value === 'stall') {
                $type = Stall::class;
            } else if ($value === 'market') {
                $type = Market::class;
            }

            $this->crud->addClause('where', 'origin_type', $type);
        });

        $this->crud->addButtonFromView('top', 'select_checklist_stall', 'select_checklist_stall', 'end');
        $this->crud->addButtonFromView('top', 'select_checklist_market', 'select_checklist_market', 'end');
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

    public function selectChecklist(Request $request, $type)
    {
        $this->crud->hasAccessOrFail('create');

        // save session selected stall
        if ($request->has('stall')) {
            session(['selected_stall' => $request->input('stall')]);
        }

        $this->data['crud'] = $this->crud;
        $this->data['type'] = $type;

        if ($type === 'market' && !auth()->user()->hasMarketSelect()) {
            return view('admin.maps.index', $this->data);
        }

        $this->data['checklists'] = ChecklistType::where('type', $type)->filterByMarket()->get();

        return view('admin.checklist.select', $this->data);
    }

    public function createChecklist($type, $id)
    {
        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->crud->setupDefaultSaveActions();
        $this->data['crud'] = $this->crud;
        $this->data['type'] = $type;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add') . ' ' . $this->crud->entity_name;
        $this->data['checklist'] = $checklist = ChecklistType::where('type', $type)->where('id', $id)->filterByMarket()->firstOrFail();

        $market_id = Cache::get('market' . auth()->user()->id);
        if ($type === 'stall') {
            $auxType = Stall::class;
            if (session('selected_stall')) {
                $origin = Stall::find(session('selected_stall'));
            } else {
                // search 5 stalls with lest checkbox of this type
                $origin = Stall::select(\DB::raw('stalls.id, stalls.num, count(checklists.origin_id) as nChecks'))
                    ->leftJoin('checklists', function ($join) use ($checklist, $auxType) {
                        $join->on('checklists.origin_id', '=', 'stalls.id');
                        $join->where('checklists.origin_type', $auxType);
                        $join->where('checklists.checklist_type_id', $checklist->id);
                    })
                    ->activeTitular()
                    ->when($market_id, function ($query) use ($market_id) {
                        return $query->where('market_id', $market_id);
                    })
                    ->where(function ($query) use ($checklist) {
                        if ($checklist->allowed_sector) {
                            $query->whereHas('auth_prods', function ($q) use ($checklist) {
                                $q->whereIn('sector_id', json_decode($checklist->allowed_sector));
                            });
                        }

                        if ($checklist->forbidden_sector) {
                            $query->whereHas('auth_prods', function ($q) use ($checklist) {
                                $q->whereNotIn('sector_id', json_decode($checklist->forbidden_sector));
                            });
                        }
                    })
                    ->groupBy('stalls.id')
                    ->orderBy('nChecks')
                    ->orderByRaw('rand()')
                    ->first();
            }
        } elseif ($type === 'market') {
            if (!auth()->user()->hasMarketSelect()) {
                return view('admin.maps.index', $this->data);
            } else {
                $auxType = Market::class;
                $origin = Market::where('id', $market_id)->first();
            }
        } else {
            abort(500, 'Incorrect checklist type');
        }

        if (!$origin) {
            abort(404, 'No hi ha cap parada o mercat elegible per fer un checklist.');
        }

        $this->data['origin'] = $origin;
        // dd($stalls);
        // ->where(function ($query) use ($checklist) {
        //     if ($checklist->allowed_sector) {
        //         $query->whereHas('authprod', function ($q) use ($checklist) {
        //             $q->whereIn('sector_id', json_decode($checklist->allowed_sector));
        //         });
        //     }

        //     if ($checklist->forbidden_sector) {
        //         $query->whereHas('authprod', function ($q) use ($checklist) {
        //             $q->whereNotIn('sector_id', json_decode($checklist->forbidden_sector));
        //         });
        //     }
        // })

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('admin.checklist.create', $this->data);
    }

    public function saveChecklist($type, $id)
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setupDefaultSaveActions();

        // forget selected_stall
        session()->forget('selected_stall');

        // get ChecklistType
        $checklistType = ChecklistType::where('type', $type)->where('id', $id)->firstOrFail();
        // get current questions for ChecklistType
        $questions = ChecklistQuestion::where('visible', true)->whereHas('checklist_group.checklist_type', function ($q) use ($checklistType) {
            $q->where('id', $checklistType->id);
        })->get();

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        if ($type === 'stall') {
            // search 5 stalls with lest checkbox of this type
            $origin = Stall::find($request->input('origin'));
        } elseif ($type === 'market') {
            $origin = Market::find($request->input('origin'));;
        } else {
            abort(422);
        }

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $checklist = $origin->checklists()->create([
            'checklist_type_id' => $checklistType->id,
            'all_ok' => $request->has('all_ok')
        ]);
        $this->data['entry'] = $this->crud->entry = $checklist;

        // create answer
        foreach ($questions as $question) {
            $answer = $checklist->checklist_answers()->create([
                'checklist_question_id' => $question->id,
                'is_check' => (bool) $request->input('question_' . $question->id),
                'comment' => $request->input('text_' . $question->id),
            ]);

            if ($request->input('filepond_' . $answer->checklist_question_id)) {
                // Save image
                $answer->saveImage($request->input('filepond_' . $answer->checklist_question_id));
                $answer->save();
            }
        }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($checklist->getKey());
    }

    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        // get the info for that entry

        $this->data['entry'] = $entry = $this->crud->getEntryWithLocale($id);
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit') . ' ' . $this->crud->entity_name;
        $this->data['id'] = $id;

        $this->data['checklist'] = $entry->checklist_type;
        $this->data['origin'] =  $entry->origin;
        $this->data['groups'] = ChecklistAnswer::where('checklist_id', $id)->with('checklist_question.checklist_group')
            ->get()->groupBy('checklist_question.checklist_group.name');

        return view('admin.checklist.edit', $this->data);
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // forget selected_stall
        session()->forget('selected_stall');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // update the row in the db
        $id = $request->get($this->crud->model->getKeyName());
        $item = $this->crud->update(
            $id,
            $this->crud->getStrippedSaveRequest($request)
        );

        // update Answers
        $answers = ChecklistAnswer::where('checklist_id', $id)->with('checklist_question.checklist_group')->get();
        foreach ($answers as $answer) {
            if ($request->input('question_' . $answer->checklist_question_id)) {
                $answer->is_check = true;
                $answer->comment = $request->input('text_' . $answer->checklist_question_id);

                if ($request->input('filepond_' . $answer->checklist_question_id)) {
                    // Save image
                    $answer->saveImage($request->input('filepond_' . $answer->checklist_question_id));
                } else {
                    // delete image
                    $answer->deleteImage();
                }
            } else {
                $answer->is_check = false;
                $answer->comment = '';
                // delete image
                $answer->deleteImage();
            }

            $answer->save();
        }

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($id);
    }
}
