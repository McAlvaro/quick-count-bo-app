<?php

namespace App\Filament\Widgets;

use App\Models\Precinct;
use App\Models\Vote;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\DB;

class VotesDeputyApexChart extends ApexChartWidget
{
    protected static ?string $chartId = 'votesDeputyApexChart';

    protected static ?string $heading = 'Estadísticas - DIPUTADO';

    protected static ?int $sort = 3;

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
            ->where('candidates.type', 'DIPUTADO');

        if ($this->filter !== 'all' && $this->filter !== null) {
            $votesQuery->where('precincts.id', $this->filter);
        }

        $votesPerParty = $votesQuery
            ->groupBy('parties.id', 'parties.name', 'parties.color')
            ->select('parties.name', 'parties.color', DB::raw('SUM(votes.quantity) as total'))
            ->get();

        $labels = $votesPerParty->pluck('name')->toArray();
        $series = $votesPerParty->pluck('total')->map(fn($v) => (int) $v)->toArray();
        $colors = $votesPerParty->pluck('color')->map(fn($c) => $c ?? '#808080')->toArray();

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 380,
            ],
            'series' => $series,
            'labels' => $labels,
            'colors' => $colors,
            'legend' => [
                'position' => 'bottom',
                'fontFamily' => 'inherit',
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'labels' => [
                            'show' => true,
                            'total' => [
                                'show' => true,
                                'label' => 'Total Votos',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<JS
        {
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            total: {
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => {
                                        return a + b
                                    }, 0)
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return value + ' votos'
                    }
                }
            }
        }
        JS);
    }
}
