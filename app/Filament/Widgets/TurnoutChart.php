<?php

namespace App\Filament\Widgets;

use App\Models\Precinct;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class TurnoutChart extends ApexChartWidget
{
    protected static ?string $chartId = 'turnoutChart';

    protected static ?string $heading = 'Participación por Recinto';

    protected static ?int $sort = 1; // Show first

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'top10';

    protected function getFilters(): ?array
    {
        return [
            'top10' => 'Top 10 Recintos',
            'all' => 'Todos (Cuidado: Puede ser lento)',
        ];
    }

    protected function getOptions(): array
    {
        $precinctsQuery = Precinct::query()->with(['tables.votes']);
        
        if ($this->filter === 'top10') {
            $precinctsQuery->limit(10);
        }
        
        $precincts = $precinctsQuery->get();
        
        $categories = [];
        $validData = [];
        $blankData = [];
        $nullData = [];
        
        foreach ($precincts as $precinct) {
            $tables = $precinct->tables;
            
            $null = $tables->sum('null_votes');
            $blank = $tables->sum('blank_votes');
            
            // Valid votes: Sum of all votes in all tables of this precinct
            $valid = 0;
            foreach($tables as $table) {
                $valid += $table->votes->sum('quantity');
            }
            
            $categories[] = $precinct->name;
            $validData[] = $valid;
            $blankData[] = $blank;
            $nullData[] = $null;
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 400,
                'stacked' => true,
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                ],
            ],
            'series' => [
                [
                    'name' => 'Votos Válidos',
                    'data' => $validData,
                    'color' => '#22c55e', // Green
                ],
                [
                    'name' => 'Blancos',
                    'data' => $blankData,
                    'color' => '#eab308', // Yellow
                ],
                [
                    'name' => 'Nulos',
                    'data' => $nullData,
                    'color' => '#ef4444', // Red
                ],
            ],
            'xaxis' => [
                'categories' => $categories,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'legend' => [
                'position' => 'top',
                'fontFamily' => 'inherit',
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'tooltip' => [
                'shared' => true,
                'intersect' => false,
            ],
        ];
    }
}
