<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\GovernorVotesChart;
use App\Filament\Widgets\MayorVotesChart;
use App\Filament\Widgets\TurnoutChart;
use App\Filament\Widgets\VoteDeputyEspApexChart;
use App\Filament\Widgets\VotesApexChart;
use App\Filament\Widgets\VotesBarChart;
use App\Filament\Widgets\VotesDeputyApexChart;
use Filament\Pages\Page;

class VotesDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static string $view = 'filament.pages.votes-dashboard';

    protected static ?string $title = 'Estadísticas';

    public static function getWidgets(): array
    {
        return [
            GovernorVotesChart::class,
            MayorVotesChart::class,
            TurnoutChart::class,
            /* VotesApexChart::class, */
            /* VotesBarChart::class, */
            /* VotesDeputyApexChart::class, */
            /* VoteDeputyEspApexChart::class, */
        ];
    }

    public function getVisibleWidgets(): array
    {
        return [
            GovernorVotesChart::class,
            MayorVotesChart::class,
            TurnoutChart::class,
            /* VotesApexChart::class, */
            /* VotesBarChart::class, */
            /* VotesDeputyApexChart::class, */
            /* VoteDeputyEspApexChart::class, */
        ];
    }
}
