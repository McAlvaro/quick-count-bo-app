<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\VotesDeputyChart;
use App\Filament\Widgets\VotesChart;
use Filament\Pages\Page;

class VotesDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static string $view = 'filament.pages.votes-dashboard';

    protected static ?string $title = 'Estadísticas';

    public static function getWidgets(): array
    {
        return [
            VotesChart::class,
            VotesDeputyChart::class
        ];
    }

    public function getVisibleWidgets(): array
    {
        return [
            VotesChart::class,
            VotesDeputyChart::class
        ];
    }
}
