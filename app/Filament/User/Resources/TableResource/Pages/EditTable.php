<?php

namespace App\Filament\User\Resources\TableResource\Pages;

use App\Filament\User\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditTable extends EditRecord
{
    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /* Actions\DeleteAction::make(), */];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Registra la fecha y usuario si es update
        $data['registered_at'] = now();
        $data['registered_by'] = Auth::id();

        return $data;
    }
}
