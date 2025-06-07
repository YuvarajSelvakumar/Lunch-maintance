<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlySummaryExport implements FromArray, WithHeadings
{
    protected $summary;
    protected $pricing;
    protected $month;

    public function __construct($summary, $pricing, $month)
    {
        $this->summary = $summary;
        $this->pricing = $pricing;
        $this->month = $month;
    }

    public function array(): array
    {
        return [
            ['Monthly Summary for ' . $this->month],
            [],
            ['Meal Type', 'Total Count', 'Unit Price (₹)', 'Total Cost (₹)'],
            ['Veg', $this->summary['total_veg'], $this->pricing->veg_price, $this->summary['total_veg'] * $this->pricing->veg_price],
            ['Egg', $this->summary['total_egg'], $this->pricing->egg_price, $this->summary['total_egg'] * $this->pricing->egg_price],
            ['Chicken', $this->summary['total_chicken'], $this->pricing->chicken_price, $this->summary['total_chicken'] * $this->pricing->chicken_price],
            [],
            ['Total Monthly Cost', '', '', '₹' . $this->summary['total_cost']],
        ];
    }

    public function headings(): array
    {
        return [];
    }
}
