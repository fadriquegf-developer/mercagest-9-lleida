<?php

namespace App\Http\Controllers\Admin;

use App\Models\Calendar;
use App\Models\Stall;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MapController extends CrudController
{
    public function hasMarketMapViewCreate()
    {
        return file_exists(resource_path('views/admin/maps/demo/m' . Cache::get('market'. auth()->user()->id) . '.blade.php'));
    }

    public function index()
    {
        $user = backpack_user();
        if(!$user->hasPermissionTo('maps')){
            abort(403);
        }

        $this->data['crud'] = $this->crud;
        if (!auth()->user()->hasMarketSelect() || !$this->hasMarketMapViewCreate()) {
            return view('admin.maps.index', $this->data);
        }
        $currentMap = Cache::get('market' . auth()->user()->id);
        $tenant = app('tenant')->name;

        $this->data['path_bg'] = '';
        $this->data['today'] = request()->date ?? Carbon::now()->toDateString();
        $this->data['path_bg'] = "images/maps/". $tenant ."/fons" . $currentMap . '.png';
        $this->data['base_url'] = url('/');

        $date = $this->data['today'];

        $calendar = Calendar::filterByMarket()->filterByDate($date)->first();
        $this->data['has_day'] = (int)!is_null($calendar);

        if (!$calendar) {
            $calendar = new Calendar();
            $calendar->id = 0;
        }
        $this->data['day'] = $calendar;

        $this->data['date'] = $date;

        // map initial config
        $this->data['x'] = config("mercagest.tenants.". $tenant .".maps.$currentMap.x", 0);
        $this->data['y'] = config("mercagest.tenants.". $tenant .".maps.$currentMap.y", 0);
        $this->data['zoom'] = config("mercagest.tenants.". $tenant .".maps.$currentMap.zoom", 0);

        $this->data['stalls'] = $this->getStalls($date);
        //dd( $this->data['stalls']);
        return view('admin.maps.base_map', $this->data);
    }

    public function getStalls($date){
        $stalls = Stall::with([
            'auth_prods',
            'historicals',
            'historicals.absences' => function ($query) use ($date) {
                return $query->filterByDate($date);
            }
        ])->filterByMarket()
            ->activeTitular()
            ->visible()
            ->get();

        foreach ($stalls as $stall) {
            if($stall->absences->count()){
                $stall->absence_type = $stall->absences->first()->type;
            }
            
            $stall->has_absence = $stall->absences()->filterByDate($date)->count() > 0;
            $stall->no_has_owner = is_null($stall->titular_info);
        }

        return $stalls;
    }
}
