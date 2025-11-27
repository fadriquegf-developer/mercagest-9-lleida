<?php

namespace App\Console\Commands;

use App\Models\Historical;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckHistoricals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:historicals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para revisar que un historial, si el dia de hoy es igual a la fecha de vigencia, entonces significara que debe darse de baja el titular';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();
        $historicals = Historical::where('end_vigencia', $today)->get();
        if($historicals->count()){
            foreach($historicals as $historical){
                $historical->ends_at = $today;
                $historical->save();
            }
        }
        
        return 'Cron Success';
    }
}
