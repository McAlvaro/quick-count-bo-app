<?php

namespace App\Filament\User\Resources\TableResource\Pages;

use App\Filament\User\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTable extends CreateRecord
{
    protected static string $resource = TableResource::class;
}
