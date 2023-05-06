<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class ApexCharts extends Component
{
    public string $chartId;

    public $seriesData;
    public $categories;
    public $seriesName;

    public function __construct($chartId, $seriesData, $categories, $seriesName = '')
    {
        $this->chartId = $chartId;
        $this->seriesData = $seriesData;
        $this->categories = $categories;
        $this->seriesName = $seriesName ?? 'Series';
    }

    public function render()
    {
        return view('components.apex-charts');
    }
}
