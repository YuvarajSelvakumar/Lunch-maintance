<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlySummaryExport implements FromArray, WithHeadings, WithTitle
{
    protected $summary;
    protected $month;

    public function __construct($summary, $month)
    {
        $this->summary = $summary;
        $this->month = $month;
    }

    public function array(): array
    {
        return [
            ['Veg', $this->summary['total_veg'], $this->summary['total_veg_cost']],
            ['Egg', $this->summary['total_egg'], $this->summary['total_egg_cost']],
            ['Chicken', $this->summary['total_chicken'], $this->summary['total_chicken_cost']],
            ['Total', '', $this->summary['total_cost']],
        ];
    }

    public function headings(): array
    {
        return ['Meal Type', 'Total Count', 'Total Amount'];
    }

    public function title(): string
    {
        return 'Monthly Summary - ' . $this->month;
    }
}
