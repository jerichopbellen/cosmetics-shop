<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class SalesPerformanceChart extends Chart
{
    public function __construct()
    {
        parent::__construct();
        
        $this->options([
            'maintainAspectRatio' => false,
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => ['beginAtZero' => true]
                    ]
                ]
            ]
        ]);
    }
}