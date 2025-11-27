<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReportExport;
use App\Models\Invoice;
use App\Models\MarketGroup;
use App\Models\Person;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

const process = [
    'settlement of receipts' => 'downloadSettlementOfReceipts',
    'accumulated amount' => 'downloadAccumulatedAmount'
];

class ReportController extends CrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(\App\Models\Rate::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report');
        $this->crud->setEntityNameStrings(__('backpack.report.single'), __('backpack.report.plural'));

        if (!backpack_user()->hasPermissionTo('report')) {
            abort(403);
        }
    }

    public function index($date = null)
    {
        $this->data['crud'] = $this->crud;
        $today = $date ?? Carbon::now()->toDateString();
        $this->data['today'] = $today;
        return view('admin.report.report', $this->data);
    }

    public function download(Request $request)
    {
        $function = process[$request->type];
        $data = $this->$function($request);
        
        $extension = $request->file_type ?? 'csv';
        $textMarketGroup = isset($request->marketgroup_id) ? MarketGroup::find($request->marketgroup_id)->title: 'tots';
        $textYear = $request->years;

        

        if($function == 'downloadAccumulatedAmount'){
            $file_name = 'import-acumulat_' . $textMarketGroup . '-' . $textYear . '.' . $extension;
            $headers = [
                'TIPUS',
                'NIF',
                'NOM',
                'ANY',
                'TOTAL ANY'
            ];
        }else{
            $file_name = 'liquidacio-ingresos_' . $textMarketGroup . '-' . $textYear . '.' . $extension;
            if(isset($request->trimestral)){
                $headers = [
                    'REBUT',
                    'TIPUS',
                    'NIF',
                    'NOM',
                    'TRIMESTRE',
                    'ANY',
                    'DATAREBUT',
                    'BANC',
                    'IMPORT'
                ];
            }else{
                $headers = [
                    'REBUT',
                    'TIPUS',
                    'NIF',
                    'NOM',
                    'MES',
                    'ANY',
                    'DATAREBUT',
                    'BANC',
                    'IMPORT'
                ];
            }
            
        }

        if($extension == 'csv'){
            return Excel::download(new ReportExport($headers, $data), $file_name, \Maatwebsite\Excel\Excel::CSV);
        }else{
            return Excel::download(new ReportExport($headers, $data), $file_name, \Maatwebsite\Excel\Excel::XLS);
        }

    }

    public function downloadSettlementOfReceipts($request)
    {
        $invoices = Invoice::where('status', 1)->whereNot('total', 0)->with('person', 'market_group')->orderBy(
            Person::select('name')
                ->whereColumn('persons.id', 'invoices.person_id')
        );

        if (isset($request->trimestral)) {
            $invoices = $invoices->where('years', $request->years)->where('trimestral',  $request->trimestral);
        } else {
            $invoices = $invoices->where('years', $request->years)->where('month',  $request->month);
        }

        if (isset($request->marketgroup_id)) {
            $invoices = $invoices->where('market_group_id', $request->marketgroup_id);
        }

        $data = [];
        if(isset($request->trimestral)){
            foreach ($invoices->get() as $invoice) {
                $trimestre = ['1r trimestre', '2n trimestre', '3r trimestre', '4t trimestre'];
                $data_info = [
                    $invoice->id,
                    $invoice->market_group->gtt_type,
                    $invoice->person->dni,
                    $invoice->person->name ?? '',
                    $trimestre[$invoice->trimestral-1],
                    $invoice->years,
                    $invoice->created_at,
                    $invoice->person->iban,
                    $invoice->total
                ];
                array_push($data, $data_info);
            }
        }else{
            $month = ['Gener','Febrer','Març','Abril','Maig','Juny','Juliol','Agost','Setembre','Octubre','Novembre','Desembre'];
            foreach ($invoices->get() as $invoice) {
                $data_info = [
                    $invoice->id,
                    $invoice->market_group->gtt_type,
                    $invoice->person->dni,
                    $invoice->person->name ?? '',
                    $month[$invoice->month-1],
                    $invoice->years,
                    $invoice->created_at,
                    $invoice->person->iban,
                    $invoice->total
                ];
                array_push($data, $data_info);
            }
        }
        

        return $data;

    }

    public function downloadAccumulatedAmount($request)
    {
        $invoices = Invoice::where('status', 1)->whereNot('total', 0)->selectRaw('persons.dni as dni,persons.name as name, years as any, sum(total) as total_any, market_group_id as marketgroup')
            ->leftJoin('persons', 'persons.id', '=', 'invoices.person_id')->orderBy(
                Person::select('name')
                    ->whereColumn('persons.id', 'invoices.person_id')
            );

        if (isset($request->marketgroup_id)) {
            $invoices = $invoices->where('market_group_id', $request->marketgroup_id);
        }

        if (isset($request->years)) {
            $invoices = $invoices->where('years', $request->years);
            $invoices = $invoices->groupBy('persons.id');
        }

        $data = [];
        foreach ($invoices->get() as $invoice) {
            $data_info = [
                MarketGroup::find($invoice->marketgroup)->type,
                $invoice->dni,
                $invoice->name,
                $invoice->any,
                $invoice->total_any
            ];
            array_push($data, $data_info);
        }

        return $data;
        
    }
}
