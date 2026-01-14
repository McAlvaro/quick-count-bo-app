<?php

namespace App\Filament\Widgets;

use App\Models\Precinct;
use App\Models\Vote;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\DB;

class VotesBarChart extends ApexChartWidget
{
    protected static ?string $chartId = 'votesBarChart';

    protected static ?string $heading = 'Estadísticas - PRESIDENTE (Barras)';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        $precincts = Precinct::query()
            ->pluck('name', 'id')
            ->toArray();

        return ['all' => 'Todos'] + $precincts;
    }

    protected function getOptions(): array
    {
        $votesQuery = Vote::query()
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
            ->join('parties', 'candidates.party_id', '=', 'parties.id')
            ->join('tables', 'votes.table_id', '=', 'tables.id')
            ->join('precincts', 'tables.precinct_id', '=', 'precincts.id')
            ->where('candidates.type', 'PRESIDENTE');

        if ($this->filter !== 'all' && $this->filter !== null) {
            $votesQuery->where('precincts.id', $this->filter);
        }

        $votesPerParty = $votesQuery
            ->groupBy('parties.id', 'parties.name', 'parties.color')
            ->select('parties.name', 'parties.color', DB::raw('SUM(votes.quantity) as total'))
            ->get();

        $labels = $votesPerParty->pluck('name')->toArray();
        $data = $votesPerParty->pluck('total')->map(fn($v) => (int) $v)->toArray();
        $colors = $votesPerParty->pluck('color')->map(fn($c) => $c ?? '#808080')->toArray();

        $total = array_sum($data);

        $labelsWithPct = array_map(function ($label, $value) use ($total) {
             $pct = $total > 0 ? round(($value / $total) * 100, 1) : 0;
             return sprintf('%s (%.1f%%)', $label, $pct);
        }, $labels, $data);

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 380,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'grid' => [
                'padding' => [
                    'top' => 30, // Give space for data labels
                ],
            ],
            'series' => [
                [
                    'name' => 'Votos',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $labelsWithPct,
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => $colors,
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 4,
                    'horizontal' => false,
                    'distributed' => true,
                    'dataLabels' => [
                        'position' => 'top',
                    ],
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
                'offsetY' => -25,
                'style' => [
                    'fontSize' => '12px',
                    'colors' => ["#304758"]
                ],
            ],
            'legend' => [
                'show' => true,
                'position' => 'bottom',
                'fontFamily' => 'inherit',
            ],
        ];
    }

    protected function extraJsOptions(): ?RawJs
    {
        $votesQuery = Vote::query()
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
            ->join('parties', 'candidates.party_id', '=', 'parties.id')
            ->join('tables', 'votes.table_id', '=', 'tables.id')
            ->join('precincts', 'tables.precinct_id', '=', 'precincts.id')
            ->where('candidates.type', 'PRESIDENTE');

        if ($this->filter !== 'all' && $this->filter !== null) {
            $votesQuery->where('precincts.id', $this->filter);
        }

        $total = $votesQuery->sum('quantity');
        
        // Ensure total is at least 1 to avoid division by zero in JS
        $total = $total > 0 ? $total : 1;

        return RawJs::make(<<<JS
        {
            dataLabels: {
                formatter: function (val, opts) {
                    let total = {$total};
                    let percent = (val / total) * 100;
                    return val + ' (' + percent.toFixed(1) + '%)';
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        let total = {$total};
                        let percent = (value / total) * 100;
                        return value + ' votos (' + percent.toFixed(1) + '%)';
                    }
                }
            }
        }
        JS);
    }
}
