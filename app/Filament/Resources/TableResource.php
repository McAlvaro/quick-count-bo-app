<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TableResource\Pages;
use App\Filament\Resources\TableResource\RelationManagers;
use App\Models\Party;
use App\Models\Precinct;
use App\Models\Table as TableModel;
use Filament\Forms;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use function Livewire\Volt\layout;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $parties = Party::orderBy('name')->get();

        $voteFields = [];

        foreach ($parties as $party) {
            $voteFields[] = Forms\Components\TextInput::make("candidate_{$party->id}")
                ->label($party->name . ' - ' . ucfirst(strtolower($party->name)))
                ->numeric()
                ->minValue(0)
                ->default(0)
                ->required();
        }
        return $form
            ->schema([
                Forms\Components\Select::make('precinct_id')
                    ->label('Precinct')
                    ->options(Precinct::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn(callable $set) => $set('table_id', null)),

                Forms\Components\Select::make('id')
                    ->label('Table Number')
                    ->options(function (callable $get) {
                        $precinctId = $get('precinct_id');
                        if (!$precinctId) {
                            return [];
                        }
                        return TableModel::where('precinct_id', $precinctId)
                            ->orderBy('number')
                            ->pluck('number', 'id');
                    })
                    ->searchable()
                    ->required(),

                /* Forms\Components\Fieldset::make('Votes by Candidate') */
                /*     ->schema($voteFields) */
                /*     ->columns(2), */

                Repeater::make('votes')
                    ->grid(2)
                    ->relationship(name: 'votes')
                    ->label(label: 'Votos, por Candidato')
                    ->schema([
                        Select::make('candidate_id')
                            ->relationship(
                                'candidate',
                                'name',
                                fn($query) => $query->with('party') // Eager loading para evitar N+1
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->type} - {$record->party->acronym}")
                            ->label('Candidato')
                            ->preload()
                            ->searchable(),

                        TextInput::make(name: 'quantity')
                            ->numeric()
                            ->required()
                            ->label(label: 'Numero de Votos')

                    ])->columnSpan(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        $parties = Party::orderBy('created_at')->get();

        $columns = [
            TextColumn::make('precinct.name')
                ->label('Precinct')
                ->sortable()
                ->searchable(),

            TextColumn::make('number')
                ->label('Table Number')
                ->sortable()
                ->searchable(),
        ];

        foreach ($parties as $party) {

            $columns[] = TextColumn::make("party_{$party->id}_votes")
                ->label($party->acronym)
                ->getStateUsing(function (TableModel $record) use ($party) {
                    $votesPresident = $record->votes
                        ->filter(fn($vote) => $vote->candidate
                            && $vote->candidate->party_id === $party->id
                            && strtoupper($vote->candidate->type) === 'PRESIDENTE')
                        ->sum('quantity');

                    $votesDeputy = $record->votes
                        ->filter(fn($vote) => $vote->candidate
                            && $vote->candidate->party_id === $party->id
                            && strtoupper($vote->candidate->type) === 'DIPUTADO')
                        ->sum('quantity');

                    $votesDeputyEsp = $record->votes
                        ->filter(fn($vote) => $vote->candidate
                            && $vote->candidate->party_id === $party->id
                            && strtoupper($vote->candidate->type) === 'DIPUTADO_ESPECIAL')
                        ->sum('quantity');

                    return "Presidente: {$votesPresident}<br>Diputado: {$votesDeputy} <br>Diputado Especial: {$votesDeputyEsp}";
                })
                ->html() // Importante para que el <br> funcione
                ->sortable(false)
                ->extraAttributes(['class' => 'text-left']);
        }

        return $table
            ->columns($columns)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }
}
