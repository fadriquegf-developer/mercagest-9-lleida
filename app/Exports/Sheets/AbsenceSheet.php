<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use App\Models\Absence;

class AbsenceSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $date;

    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * @var Invoice $invoice
     */
    public function map($absence): array
    {
        return [
            __('backpack.absences.types.' . $absence->type),
            $absence->start->format('d-m-Y'),
            $absence->end->format('d-m-Y'),
            $absence->stall->num_market,
            $absence->cause,
            basename($absence->getDocumentUrl())
        ];
    }

    public function headings(): array
    {
        return [
            __('backpack.persons.show.absences.table.type'),
            __('backpack.stalls.show.absences.table.start'),
            __('backpack.stalls.show.absences.table.end'),
            __('backpack.stalls.show.absences.table.stall'),
            __('backpack.stalls.show.absences.table.cause'),
            __('backpack.stalls.show.absences.table.document')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Absence::query()->with('stall')->byMarketSelected()->filterByDate($this->date);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return  __('backpack.absences.plural');
    }
}
