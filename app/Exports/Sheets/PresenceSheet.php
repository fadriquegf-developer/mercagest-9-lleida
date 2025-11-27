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
use App\Models\Stall;

class PresenceSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $date;

    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * @var Invoice $invoice
     */
    public function map($stall): array
    {
        return [
            $stall->titular,
            $stall->num_market,
            $stall->market->name ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            __('backpack.report.daily_report.table.person_name'),
            __('backpack.report.daily_report.table.stall'),
            __('backpack.report.daily_report.table.market'),
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
        return Stall::filterByMarket()->presenceByDate($this->date);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return  __('backpack.report.daily_report.presence');
    }
}
