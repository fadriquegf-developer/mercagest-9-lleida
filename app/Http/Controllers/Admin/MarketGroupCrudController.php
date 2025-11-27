<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MarketGroupRequest;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Jobs\GeneratePDF;
use App\Models\MarketGroup;

/**
 * Class MarketGroupCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MarketGroupCrudController extends CrudController
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
        $this->crud->setModel(MarketGroup::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/market-group');
        $this->crud->setEntityNameStrings(__('backpack.market_groups.single'), __('backpack.market_groups.plural'));
        $this->setPermissions('market_groups');
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
            ],
            ['name' => 'type', 'label' => __('backpack.market_groups.type')],
            ['name' => 'gtt_type', 'label' => __('backpack.market_groups.gtt_type')],
            ['name' => 'title', 'label' => __('backpack.market_groups.title')],
            [
                'name' => 'payment',
                'label' => __('backpack.market_groups.payment'),
                'type'    => 'select_from_array',
                'options' => ['mensual' => 'Mensual', 'trimestral' => 'Trimestral'],
            ],
        ]);

        $this->crud->addButtonFromView('line', 'generate', 'generate', 'beginning');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MarketGroupRequest::class);

        $this->crud->addFields([
            [
                'name'  => 'status',
                'type'  => 'switch',
                'label'    => 'Activar/Desactivar',
                'color'    => 'primary',
                'onLabel' => '✓',
                'offLabel' => '✕',
            ],
            ['name' => 'type', 'label' => __('backpack.market_groups.type')],
            ['name' => 'gtt_type', 'label' => __('backpack.market_groups.gtt_type')],
            ['name' => 'title', 'label' => __('backpack.market_groups.title')],
            [
                'name'        => 'payment',
                'label'       =>  __('backpack.market_groups.payment'),
                'type'        => 'select_from_array',
                'options'     => ['mensual' => 'Mensual', 'trimestral' => 'Trimestral'],
                'allows_null' => false,
                'default'     => 'mensual',
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
        $this->setupCreateOperation();
    }

    public function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function downloadView($id)
    {
        $marketGroup = \App\Models\MarketGroup::find($id);
        $data['crud'] = $this->crud;
        $data['marketGroup'] = $marketGroup;

        return view('admin.market-group.download', $data);
    }

    public function progress($id)
    {
        // getting appropriate batch_id
        $batch = \DB::table('job_batches')
            ->where('name', $id)
            ->latest()
            ->first();

        // response back to the client
        if ($batch) {
            $progress = $batch->total_jobs > 0 ? round((($batch->total_jobs - $batch->pending_jobs) / $batch->total_jobs) * 100) : 0;
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $batch->id,
                    'name' => $batch->name,
                    'total_jobs' => $batch->total_jobs,
                    'pending_jobs' => $batch->pending_jobs,
                    'failed_jobs' => $batch->failed_jobs,
                    'created_at' => $batch->created_at,
                    'finished_at' => $batch->finished_at,
                    'progress' => $progress,
                ],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Batch not found!",
            ]);
        }
    }

    public function startGeneratePDFs($id)
    {
        $marketGroup = \App\Models\MarketGroup::find($id);
        $stalls = \App\Models\Stall::activeTitular()->filterByMarket()
            ->where('market_group_id', $marketGroup->id)->get();
        
        // delete previous zip
        $zipName = Str::slug($marketGroup->title, '-');
        Storage::disk('local')->delete("/market-group/zips/{$zipName}.zip");


        $tasks = [];
        foreach ($stalls as $stall) {
            $tasks[] = new GeneratePDF($stall->getTitular(), $marketGroup);
        }

        $batch = Bus::batch($tasks)->name($marketGroup->id)->dispatch();

        return redirect(backpack_url("market-group/{$marketGroup->id}/pre-download"));
    }

    public function downloadPDFs($id)
    {
        $marketGroup = \App\Models\MarketGroup::find($id);
        $zipName = Str::slug($marketGroup->title, '-');
        $zipFilePath = Storage::disk('local')->path("/market-group/zips/{$zipName}.zip");

        return response()->download($zipFilePath);
    }
}
