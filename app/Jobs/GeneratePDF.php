<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;
use App\Models\Person;
use App\Models\MarketGroup;


class GeneratePDF implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $person;
    public $marketGroup;

    public function __construct(Person $person, MarketGroup $marketGroup)
    {
        $this->person = $person;
        $this->marketGroup = $marketGroup;
    }

    public function handle()
    {
        $batchId = $this->batch()->id;
        $fileName = "{$this->person->id}.pdf";

        $pdfFilePath = '/market-group/' . $this->marketGroup->id . '/' . $fileName;
        $pdf = \App\Services\PersonService::getAccreditation($this->person);
        Storage::disk('local')->put($pdfFilePath, $pdf->output());

        $this->updateZipArchive($batchId, $pdfFilePath, $fileName);
    }

    private function updateZipArchive($batchId, $pdfFilePath, $fileName)
    {
        $path = '/market-group/zips';
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        $zip = new ZipArchive();
        $zipName = Str::slug($this->marketGroup->title, '-');
        $zipFilePath = Storage::disk('local')->path("{$path}/{$zipName}.zip");

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $zip->addFromString($fileName, Storage::disk('local')->get($pdfFilePath));
            $zip->close();
        } else {
            logger('Failed opening zip file:' . $zipFilePath);
        }
    }
}
