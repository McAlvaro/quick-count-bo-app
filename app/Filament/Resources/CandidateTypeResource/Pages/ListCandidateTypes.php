<?php

namespace App\Filament\Resources\CandidateTypeResource\Pages;

use App\Filament\Resources\CandidateTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCandidateTypes extends ListRecords
{
    protected static string $resource = CandidateTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
