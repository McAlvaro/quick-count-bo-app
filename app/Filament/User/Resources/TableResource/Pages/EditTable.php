<?php

namespace App\Filament\User\Resources\TableResource\Pages;

use App\Filament\User\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTable extends EditRecord
{
    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /* Actions\DeleteAction::make(), */
        ];
    }
}
