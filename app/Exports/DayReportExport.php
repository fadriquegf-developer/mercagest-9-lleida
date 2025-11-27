<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;
use App\Exports\Sheets\PresenceSheet;
use App\Exports\Sheets\IncidencesSheet;
use App\Exports\Sheets\AbsenceSheet;

class DayReportExport implements WithMultipleSheets
{
    use Exportable;

    protected $date;

    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new PresenceSheet($this->date),
            new IncidencesSheet($this->date),
            new AbsenceSheet($this->date)
        ];

        return $sheets;
    }
}
