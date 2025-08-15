<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrecinctResource\Pages;
use App\Filament\Resources\PrecinctResource\RelationManagers;
use App\Models\Precinct;
use Filament\Forms;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrecinctResource extends Resource
{
    protected static ?string $model = Precinct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('location')->required(),
                TextInput::make('code')->nullable(),
                HasManyRepeater::make(name: 'tables')
                    ->relationship(name: 'tables')
                    ->label(label: 'Mesas')
                    ->schema(components: [
                        TextInput::make(name: 'number')
                            ->numeric()
                            ->required()
                            ->label(label: 'Numero de Mesa')
                    ])
                    ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('location'),
                TextColumn::make('code'),
                TextColumn::make(name: 'tables_count')
                    ->counts(relationships: 'tables')
                    ->label(label: 'Mesas'),
            ])
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrecincts::route('/'),
            'create' => Pages\CreatePrecinct::route('/create'),
            'edit' => Pages\EditPrecinct::route('/{record}/edit'),
        ];
    }
}
