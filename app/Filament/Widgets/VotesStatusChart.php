<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class VotesStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            'labels' => ['Votos Válidos', 'Votos Nulos', 'Votos Blancos'],
            'datasets' => [
                [
                    'label' => 'Cantidad de Votos',
                    'data' => [10, 20, 30],
                    'backgroundColor' => ['#ffffff', '#f808080', '#fb3441'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
