<?php

namespace App\Livewire;

use App\Models\Precinct;
use App\Models\Table;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ControlPanel extends Component
{
    public $precinctFilter = 'all';

    public $tableFilter = 'all';

    public function getPrecinctsProperty()
    {
        return Precinct::pluck('name', 'id');
    }

    public function getTablesProperty()
    {
        if ($this->precinctFilter === 'all') {
            return [];
        }

        return Table::where('precinct_id', $this->precinctFilter)->pluck('number', 'id');
    }

    public function updatedPrecinctFilter()
    {
        $this->tableFilter = 'all';
    }

    public function render()
    {
        return view('livewire.control-panel', $this->getViewData())->layout('layouts.guest');
    }

    protected function getViewData(): array
    {
        // Base Query Logic
        $tableQuery = Table::query();

        if ($this->precinctFilter !== 'all') {
            $tableQuery->where('precinct_id', $this->precinctFilter);
        }

        if ($this->tableFilter !== 'all') {
            $tableQuery->where('id', $this->tableFilter);
        }

        $totalTablesCount = $tableQuery->count();

        $totalSystemTables = Table::count();
        $filteredTablesIds = $tableQuery->pluck('id');

        $scrutinizedTables = Table::whereIn('id', $filteredTablesIds)
            ->has('votes')
            ->count();

        $scopeTotalTables = ($this->precinctFilter !== 'all') ? Table::where('precinct_id', $this->precinctFilter)->count() : $totalSystemTables;
        $scopeScrutinized = Table::whereIn('id', $filteredTablesIds)->has('votes')->count();
        $scopePct = $scopeTotalTables > 0 ? ($scopeScrutinized / $scopeTotalTables) * 100 : 0;

        // 2. Votos Totales
        $validVotes = Vote::whereIn('table_id', $filteredTablesIds)->sum('quantity');
        $nullVotes = Table::whereIn('id', $filteredTablesIds)->sum('null_votes');
        $blankVotes = Table::whereIn('id', $filteredTablesIds)->sum('blank_votes');
        $totalVotes = $validVotes + $nullVotes + $blankVotes;

        // 3. Abstencion / Participation
        $eligibleVoters = Table::whereIn('id', $filteredTablesIds)->sum('total_eligible') ?? 0;
        $abstentionPct = $eligibleVoters > 0 ? (($eligibleVoters - $totalVotes) / $eligibleVoters) * 100 : 0;

        // 4. Presidential Results
        /* $presidentResults = $this->getResultsByType('PRESIDENTE', $filteredTablesIds); */
        $governorResults = $this->getResultsByType('GOBERNADOR', $filteredTablesIds);

        // 5. Deputy Results
        /* $deputyResults = $this->getResultsByType('DIPUTADO', $filteredTablesIds); */
        $mayorResults = $this->getResultsByType('ALCALDE', $filteredTablesIds);

        return [
            'scopeScrutinized' => $scopeScrutinized,
            'scopeTotalTables' => $scopeTotalTables,
            'scopePct' => $scopePct,
            'totalVotes' => $totalVotes,
            'abstentionPct' => $abstentionPct,
            /* 'presidentResults' => $presidentResults, */
            'governorResults' => $governorResults,
            /* 'deputyResults' => $deputyResults, */
            'mayorResults' => $mayorResults,
            'lastUpdate' => now()->format('H:i A'),
        ];
    }

    private function getResultsByType($type, $tableIds)
    {
        $query = Vote::query()
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
            ->join('parties', 'candidates.party_id', '=', 'parties.id')
            ->whereIn('votes.table_id', $tableIds)
            ->where('candidates.type', $type)
            ->select(
                'candidates.name as candidate_name',
                'candidates.photo_path',
                'parties.name as party_name',
                'parties.acronym',
                'parties.color',
                'parties.logo_path',
                DB::raw('SUM(votes.quantity) as votes')
            )
            ->groupBy('candidates.id', 'candidates.name', 'candidates.photo_path', 'parties.name', 'parties.acronym', 'parties.color', 'parties.logo_path')
            ->orderByDesc('votes')
            ->get();

        $totalValid = $query->sum('votes');

        return $query->map(function ($item) use ($totalValid) {
            $item->percentage = $totalValid > 0 ? ($item->votes / $totalValid) * 100 : 0;

            return $item;
        });
    }
}
