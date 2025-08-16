<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartyResource\Pages;
use App\Filament\Resources\PartyResource\RelationManagers;
use App\Models\Party;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartyResource extends Resource
{
    protected static ?string $model = Party::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Partido';
    protected static ?string $pluralModelLabel = 'Partidos';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make(name: 'name')->required()->label(label: 'Nombre de Partido'),
                TextInput::make(name: 'acronym')->required()->label(label: 'Sigla'),
                FileUpload::make(name: 'logo_path')->nullable()
                    ->image()
                    ->directory('logos'),
                Repeater::make('candidates')
                    ->label('Candidates')
                    ->relationship()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de Candidato')
                            ->required(),
                        Select::make('type')
                            ->label('Tipo de Candidato')
                            ->required()
                            ->options([
                                'PRESIDENTE' => 'PRESIDENTE',
                                'DIPUTADO'   => 'DIPUTADO',
                                'DIPUTADO_ESPECIAL' => 'DIPUTADO_ESPECIAL',
                            ])
                    ])
                    ->createItemButtonLabel('Add Candidate')
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'name')
                    ->label(label: 'Nombre'),
                TextColumn::make(name: 'acronym')
                    ->label(label: 'Sigla'),
                ImageColumn::make(name: 'logo_path')
                    ->label(label: 'Logo')->square()->height(height: 50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make()
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
            'index' => Pages\ListParties::route('/'),
            'create' => Pages\CreateParty::route('/create'),
            'edit' => Pages\EditParty::route('/{record}/edit'),
        ];
    }
}
