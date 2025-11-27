<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CalendarRequest;
use App\Http\Requests\MarketDateRequest;
use App\Models\Calendar;
use App\Models\Market;
use App\Models\Person;
use App\Models\Stall;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Prologue\Alerts\Facades\Alert;

/**
 * Class CalendarCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CalendarCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation { index as traitIndex; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \App\Traits\AdminPermissions;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Calendar::class);
        $market_id = Str::between(request()->getRequestUri(), '/admin/market/', '/calendar');
        $this->crud->setRoute('/admin/market/'.$market_id.'/calendar');
        $this->crud->setEntityNameStrings(__('backpack.calendar.single'), __('backpack.calendar.plural'));
        $this->setPermissions('calendar');
        $this->crud->setListView('admin.calendar.list');
        
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
        $this->crud->denyAccess('search');
        
        $this->crud->addButtonFromModelFunction('line', 'dayreport', 'dayreport', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'addAbsence', 'addAbsence', 'beginning');
        $this->crud->addButtonFromModelFunction('line', 'addIncedence', 'addIncedence', 'beginning');

        $market_id = request()->route('market_id');

        $this->crud->addButtonFromModelFunction('top', 'table_view', 'tableView', 'end');
        $this->crud->addButtonFromModelFunction('top', 'calendar_view', 'calendarView', 'end');

        if ($market_id != 'all'){
            $this->crud->addClause('filterByMarket', (int)$market_id);
            $this->crud->addButtonFromModelFunction('top', 'market_day', 'marketDay', 'beginning');
        }else{
            $this->crud->addClause('filterByUserMarket');
            $this->crud->addFilter([
                'name'  => 'status',
                'type'  => 'select2',
                'label' => 'Mercat'
              ], function () {
                return Market::whereIn('id', backpack_user()->my_markets->pluck('id')->toArray())->pluck('name','id')->toArray();
              }, function ($value) { // if the filter is active
                    $this->crud->addClause('where', 'market_id', $value);
            });
        }

        $this->crud->addFilter([
            'type'  => 'date_range',
            'name'  => 'from_to',
            'label' => 'Rang de Dates'
          ],
          false,
          function ($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'date', '>=', $dates->from);
            $this->crud->addClause('where', 'date', '<=', $dates->to . ' 23:59:59');
        });

        $this->crud->addColumns([
            [
                'name'  => 'date',
                'label' => __('backpack.calendar.date'),
                'type'  => 'date'
            ],
            [
                // 1-n relationship
                'label'     => 'Mercat', // Table column heading
                'type'      => 'select',
                'name'      => 'market_id', // the column that contains the ID of that connected entity;
                'entity'    => 'market', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => "App\Models\Market", // foreign key model
             ],
        ]);
    }

    /**
     * Display all rows in the database for this entity.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->data['crud'] = $this->crud;

        $market_id = request()->route('market_id');

        if ($market_id == 'all' && !auth()->user()->hasMarketSelect()) {
            return view('admin.maps.index', $this->data);
        }else if($market_id !== 'all'){
            // select market
            \App\Http\Controllers\Admin\MarketCrudController::setMarket($market_id);
        }else{
            $current_map = Cache::get('market' . auth()->user()->id);
            return redirect()->route('/market/{market_id}/calendar.index', ['market_id' => $current_map]);
        }

        // load calendar info
        $this->showCalendarDays();

        return $this->traitIndex();
    }

    public function getDatesCalendar(Person $person)
    {
        $calendar_dates = ['' => 'Select...'];
        if (!$person->id) return $calendar_dates;
        $stall = $person->historicals()->whereHas('market')->first();
        if ($stall) {
            $market_id = $stall->market->id;
            $calendar_dates = Calendar::where('market_id', $market_id)->pluck('date', 'id');
        }
        return $calendar_dates;
    }

    public function getViewMarketDates($market_id)
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->loadDefaultOperationSettingsFromConfig();
        $this->crud->setupDefaultSaveActions();

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['market_id'] = $market_id;

        $this->crud->addFields([
            [
                'name' => 'start',
                'label' => __('backpack.market_days.start'),
                'type' => 'date'
            ],
            [
                'name' => 'end',
                'label' => __('backpack.market_days.end'),
                'type' => 'date'
            ],
            [
                'name' => 'excluded_dates',
                'type' => 'hidden',
                'value' => ''
            ]
        ]);
        return view('admin.calendar.add_market_dates', $this->data);
    }

    public function createMarketDates(MarketDateRequest $request, $market_id)
    {
        $period = CarbonPeriod::between($request->start, $request->end);
        if ($market_id == 'all'){
            $markets = Market::get();
            foreach ($markets as $market){
                $this->createMarketDatesByMarket($market, $period);
            }
        }else{
            $market = Market::findOrFail($request->market_id);
            $this->createMarketDatesByMarket($market, $period);
        }

        Alert::success(trans('backpack::crud.insert_success'))->flash();
        return redirect(url('/admin/market/'.($market_id ?? 'all').'/calendar'));
    }

    public function createMarketDatesByMarket($market, $period){
        if (isset($market->days_of_week) && count($market->days_of_week) > 0){

            $days_of_week = $market->days_of_week;
            $input = [];
            $excluded_dates = request()->input('excluded_dates', []) ?: [];
            if($excluded_dates){
                $excluded_dates = explode(',', $excluded_dates);
            }

            foreach ($period as $date) {
                if (in_array($date->dayOfWeek, $days_of_week) && !in_array($date->format('d-m-Y'), $excluded_dates)) {
                    $input[] = [
                        'date' => $date->toDateString(),
                        'market_id' => $market->id,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ];
                }
            }

            if (count($input)){
                $calendar = Calendar::where('market_id', $market->id)
                    ->whereIn('date', collect($input)->pluck('date')->toArray())
                    ->pluck('date');
                if ($calendar->count()){
                    $input = collect($input)->whereNotIn('date', $calendar->toArray())->toArray();
                }
                Calendar::insert($input);
            }
        }
    }

    public function getDatesCalendarByStall(Stall $stall){
        $calendar_dates = ['' => 'Select...'];
        if (!$stall->id) return $calendar_dates;
        $market_id = $stall->market->id;
        $calendar_dates = Calendar::where('market_id', $market_id)->pluck('date', 'id');
        return $calendar_dates;
    }

    public function checkDates(Request $request)
    {
        $period = CarbonPeriod::between($request->date_start, $request->date_end);
        $market = Market::findOrFail($request->market_id);

        $days_of_week = $market->days_of_week;
        $input = [];

        foreach ($period as $date) {
            if (in_array($date->dayOfWeek, $days_of_week)) {
                $input[] = ['date' => $date->format('d-m-Y')];
            }
        }

        return ['dates' => $input, 'status' => 'ok'];
    }

    protected function showCalendarDays()
    {
        if (Cache::has('market' . auth()->user()->id)) {
            $this->crud->calendarDays = Calendar::where('market_id', Cache::get('market' . auth()->user()->id))->get()->map(function ($item, $key) {
                return [
                    'id' => $item->id,
                    'title' => $item->market->name,
                    'start' => $item->date
                ];
            })->toArray();
        }else{
            $this->crud->calendarDays = Calendar::whereIn('market_id', backpack_user()->my_markets->pluck('id')->toArray())->get()->map(function ($item, $key) {
                return [
                    'id' => $item->id,
                    'title' => $item->market->name,
                    'start' => $item->date
                ];
            })->toArray();
        }
    }
}
