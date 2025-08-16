<?php

namespace App\Filament\Widgets;

use App\Models\Precinct;
use App\Models\Vote;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoteDeputyEspChart extends ChartWidget
{
    protected static ?string $heading = 'Estadísticas - DIPUTADO ESPECIAL';

    public ?string $precinctId = null;

    protected function getFilters(): array
    {
        $precincts = Precinct::query()
            ->pluck('name', 'id')
            ->toArray(); // Ya no necesitamos el map() porque pluck() ya nos da el formato correcto

        Log::debug(json_encode(['all' => 'Todos'] + $precincts));

        $filters = ['all' => 'Todos'] + $precincts;

        return $filters;
    }

    protected function getData(): array
    {
        // Obtenemos los votos agrupados por partido de manera más eficiente
        $votesQuery = Vote::query()
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
            ->join('parties', 'candidates.party_id', '=', 'parties.id')
            ->join('tables', 'votes.table_id', '=', 'tables.id') // Unimos con tables
            ->join('precincts', 'tables.precinct_id', '=', 'precincts.id') // Y luego con precincts
            ->where('candidates.type', 'DIPUTADO_ESPECIAL');

        if ($this->filter !== 'all' && $this->filter !== null) {
            Log::debug('Filter: ' . $this->filter);
            $votesQuery->where('precincts.id', $this->filter);
        }



        $votesPerParty = $votesQuery
            ->groupBy('parties.id', 'parties.name')
            ->select('parties.name', DB::raw('SUM(votes.quantity) as total'))
            ->get();

        Log::debug(json_encode($votesPerParty));

        return [
            'datasets' => [
                [
                    'data' => $votesPerParty->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#5998f0',
                        '#cc2f1f',
                        '#56249c',
                        '#fad9d9',
                        '#148fdb',
                        '#06138f',
                        '#c46a02',
                        '#197347',
                        '#808080',
                        '#ffffff',
                        // Agrega más colores si tienes más partidos
                    ],
                ],
            ],
            'labels' => $votesPerParty->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
            'height' => 600,
            'scales' => [
                'x' => [
                    'display' => false, // Oculta el eje X
                    'grid' => [
                        'display' => false // Oculta la grilla del eje X
                    ]
                ],
                'y' => [
                    'display' => false, // Oculta el eje Y
                    'grid' => [
                        'display' => false // Oculta la grilla del eje Y
                    ]
                ]
            ]

        ];
    }
}
