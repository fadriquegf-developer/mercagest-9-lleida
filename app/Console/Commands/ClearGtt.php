<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearGtt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:gtt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpieza de carpeta GTT dentro de Storage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Storage::disk('public')->deleteDirectory('gtt');
        Storage::disk('public')->makeDirectory('gtt');

        return true;

    }
}
