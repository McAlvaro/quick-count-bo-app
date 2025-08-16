<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\TableResource\Pages;
use App\Filament\User\Resources\TableResource\RelationManagers;
use App\Models\Party;
use App\Models\Precinct;
use App\Models\Table as TableModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Voto';
    protected static ?string $pluralModelLabel = 'Votos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('precinct_id')
                    ->label('Recinto')
                    ->options(Precinct::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->disabled()
                    ->afterStateUpdated(fn(callable $set) => $set('table_id', null)),

                Forms\Components\Select::make('id')
                    ->label('Numero de Mesa')
                    ->options(function (callable $get) {
                        $precinctId = $get('precinct_id');
                        if (!$precinctId) {
                            return [];
                        }
                        return TableModel::where('precinct_id', $precinctId)
                            ->orderBy('number')
                            ->pluck('number', 'id');
                    })
                    ->disabled()
                    ->searchable()
                    ->required(),

                Repeater::make('votes')
                    ->grid(2)
                    ->relationship(name: 'votes')
                    ->label(label: 'Votos, por Candidato')
                    ->schema([
                        Forms\Components\Placeholder::make('candidate_label')
                            ->label('')
                            ->content(fn($get, $record) => "{$record->candidate->type} - {$record->candidate->party->acronym}"),

                        Select::make('candidate_id')
                            ->relationship(
                                'candidate',
                                'name',
                                fn($query) => $query->with('party') // Eager loading para evitar N+1
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->type} - {$record->party->acronym}")
                            ->preload()
                            ->label('')
                            ->hidden()
                            ->disabled()
                            ->searchable(),

                        TextInput::make(name: 'quantity')
                            ->numeric()
                            ->required()
                            ->label(label: 'Numero de Votos')

                    ])
                    ->deletable(false)
                    ->addable(false)
                    ->columnSpan(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('precinct.name')
                    ->label('Precinct')
                    ->size(size: 16)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('number')
                    ->label('Table Number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('registered_at')
                    ->label(label: 'Fecha de Registro'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(label: 'Registrar Voto'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    /* Tables\Actions\DeleteBulkAction::make(), */]),
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
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('precinct', function (Builder $query) {
                $query->whereIn('id', Auth::user()->precincts->pluck('id'));
            });
    }
}
