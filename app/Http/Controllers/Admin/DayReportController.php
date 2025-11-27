<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\DayReportExport;
use App\Models\Absence;
use App\Models\Incidences;
use App\Models\Stall;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DayReportController extends CrudController
{
    public function index($date = null)
    {
        $user = backpack_user();
        if(!$user->hasPermissionTo('day_report')){
            abort(403);
        }
        
        $this->data['crud'] = $this->crud;
        $today = $date ?? Carbon::now()->toDateString();
        $this->data['today'] = $today;
        $this->data['incidences'] = Incidences::byMarketSelected()->filterByDate($today)->get();
        $this->data['absences'] = Absence::byMarketSelected()->with('stall')->filterByDate($today)->get();
        $this->data['stalls'] = Stall::filterByMarket()->presenceByDate($today)->get();
        return view('admin.report.day_report', $this->data);
    }

    public function export($date)
    {
        $d = Carbon::parse($date);
        return (new DayReportExport($d))->download('day-report-' . $date . '.xlsx');
    }
}
