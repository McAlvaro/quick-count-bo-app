<?php

namespace App\Filament\Widgets;

use App\Models\Party;
use Filament\Support\RawJs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TurnoutChart extends ApexChartWidget
{
    protected static ?string $chartId = 'turnoutChart';

    protected static ?string $heading = 'Recintos con Mayor Votación';

    protected static ?int $sort = 99;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        $parties = Party::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        return ['all' => 'Todos los partidos'] + collect($parties)
            ->map(fn (string $name) => "Partido: {$name}")
            ->all();
    }

    protected function getOptions(): array
    {
        $results = $this->getTopPrecinctsByParty();

        $categories = $results->pluck('precinct_name')->toArray();
        $data = $results->pluck('total_votes')->map(fn ($value) => (int) $value)->toArray();

        $first = $results->first();
        $color = $first?->party_color ?? '#2563eb';

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 380,
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                    'borderRadius' => 6,
                ],
            ],
            'series' => [
                [
                    'name' => 'Votos',
                    'data' => $data,
                ],
            ],
            'colors' => [$color],
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
            'dataLabels' => [
                'enabled' => true,
                'style' => [
                    'fontFamily' => 'inherit',
                    'fontWeight' => 600,
                ],
            ],
            'legend' => [
                'show' => false,
            ],
        ];
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            dataLabels: {
                formatter: function (value) {
                    return new Intl.NumberFormat().format(value);
                }
            },
            tooltip: {
                y: {
                    formatter: function (value, opts) {
                        const totals = opts.w.globals.seriesTotals || [];
                        const total = totals.reduce((acc, curr) => acc + curr, 0);
                        const pct = total > 0 ? (value / total) * 100 : 0;
                        return new Intl.NumberFormat().format(value) + ' votos (' + pct.toFixed(1) + '%)';
                    }
                }
            }
        }
        JS);
    }

    protected function getTopPrecinctsByParty(): Collection
    {
        $query = DB::table('precincts')
            ->select([
                'precincts.name as precinct_name',
                DB::raw('SUM(votes.quantity) as total_votes'),
                DB::raw('MAX(parties.color) as party_color'),
            ])
            ->join('tables', 'tables.precinct_id', '=', 'precincts.id')
            ->join('votes', 'votes.table_id', '=', 'tables.id')
            ->join('candidates', 'candidates.id', '=', 'votes.candidate_id')
            ->join('parties', 'parties.id', '=', 'candidates.party_id')
            ->groupBy('precincts.id', 'precincts.name')
            ->orderByDesc('total_votes')
            ->limit(5);

        if ($this->filter !== 'all') {
            $query->where('parties.id', $this->filter);
        }

        return collect($query->get());
    }
}
