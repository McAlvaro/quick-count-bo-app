<?php

namespace App\Filament\Resources\PrecinctResource\Pages;

use App\Filament\Resources\PrecinctResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrecinct extends EditRecord
{
    protected static string $resource = PrecinctResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
