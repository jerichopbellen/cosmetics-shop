<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class ProductShareChart extends Chart
{
    public function __construct()
    {
        parent::__construct();

        $this->options([
            'maintainAspectRatio' => false,
            'legend' => ['display' => false]
        ]);
    }
}