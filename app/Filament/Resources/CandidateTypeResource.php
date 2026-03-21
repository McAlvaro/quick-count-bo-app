<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandidateTypeResource\Pages;
use App\Models\CandidateType;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CandidateTypeResource extends Resource
{
    protected static ?string $model = CandidateType::class;

    protected static ?string $modelLabel = 'Tipo de Candidato';

    protected static ?string $pluralModelLabel = 'Tipos de Candidatos';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Configuracion';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandidateTypes::route('/'),
            'create' => Pages\CreateCandidateType::route('/create'),
            'edit' => Pages\EditCandidateType::route('/{record}/edit'),
        ];
    }
}
