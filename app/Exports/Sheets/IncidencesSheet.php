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
use App\Models\Incidences;

class IncidencesSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $date;

    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * @var Invoice $invoice
     */
    public function map($incidence): array
    {
        return [
            date('d-m-Y', strtotime($incidence->date_incidence)),
            $incidence->title,
            $incidence->transType(),
            __('backpack.incidences.statuses.' . $incidence->status),
            $incidence->stall && $incidence->stall->market ? $incidence->stall->market->name : '',
        ];
    }

    public function headings(): array
    {
        return [
            __('backpack.stalls.show.incidences.table.date'),
            __('backpack.stalls.show.incidences.table.title'),
            __('backpack.stalls.show.incidences.table.type'),
            __('backpack.stalls.show.incidences.table.status'),
            __('backpack.stalls.show.incidences.table.market'),
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
        return Incidences::byMarketSelected()->filterByDate($this->date);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return  __('backpack.incidences.plural');
    }
}
