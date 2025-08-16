<?php

namespace App\Filament\Resources\PrecinctResource\Pages;

use App\Filament\Resources\PrecinctResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrecincts extends ListRecords
{
    protected static string $resource = PrecinctResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
