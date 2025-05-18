<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketPriceResource\Pages;
use App\Models\TicketContent;
use App\Models\TicketPrice;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketPriceResource extends Resource
{
    protected static ?string $model = TicketPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    protected static ?string $navigationLabel = 'Prix des billets';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        // Récupérer tous les contenus de billets pour la sélection
        $ticketContents = TicketContent::all()->pluck('name', 'id')->toArray();

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label('Prix (€)')
                    ->required()
                    ->numeric()
                    ->prefix('€')
                    ->step(0.01),
                Forms\Components\Toggle::make('sold_out')
                    ->label('Épuisé')
                    ->default(false),
                Forms\Components\Repeater::make('temp_contents')
                    ->label('Contenu du billet (glisser-déposer pour réordonner)')
                    ->schema([
                        Forms\Components\Select::make('content_id')
                            ->label('Contenu')
                            ->options($ticketContents)
                            ->required()
                            ->searchable(),
                    ])
                    ->reorderable()
                    ->reorderableWithDragAndDrop()
                    ->columnSpanFull()
                    ->required()
                    ->minItems(1)
                    ->live()
                    ->afterStateHydrated(function (Forms\Components\Repeater $component, $state, $record) {
                        // Si c'est une édition et qu'on a déjà des contenus
                        if ($record && !empty($record->contents) && is_array($record->contents)) {
                            $items = [];
                            foreach ($record->contents as $contentId) {
                                $items[] = ['content_id' => $contentId];
                            }
                            $component->state($items);
                        }
                    })
                    ->dehydrated(false), // Ne pas sauvegarder directement ce champ

                // Champ hidden pour stocker l'array JSON final
                Forms\Components\Hidden::make('contents')
                    ->afterStateHydrated(function ($component, $state) {
                        // Ne rien faire ici, on laisse le champ tel quel pour la BDD
                    })
                    ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                        // Convertir le format du Repeater en tableau d'IDs pour le stockage
                        $tempContents = $get('temp_contents');
                        if (!is_array($tempContents)) {
                            return [];
                        }

                        return collect($tempContents)
                            ->pluck('content_id')
                            ->filter()
                            ->values()
                            ->toArray();
                    }),

                Forms\Components\Hidden::make('salon_id')
                    ->default(function () {
                        return Filament::getTenant()->id;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('EUR'),
                Tables\Columns\IconColumn::make('sold_out')
                    ->label('Épuisé')
                    ->boolean(),
                Tables\Columns\TextColumn::make('contents')
                    ->label('Contenus')
                    ->getStateUsing(function (TicketPrice $record) {
                        if (!is_array($record->contents)) {
                            return [];
                        }

                        return collect($record->contents)
                            ->map(function ($id) {
                                $content = TicketContent::find($id);
                                return $content ? $content->name : null;
                            })
                            ->filter()
                            ->toArray();
                    })->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('sold_out')
                    ->label('Disponibilité')
                    ->placeholder('Tous')
                    ->trueLabel('Épuisés uniquement')
                    ->falseLabel('Disponibles uniquement')
                    ->queries(
                        true: fn(Builder $query) => $query->where('sold_out', true),
                        false: fn(Builder $query) => $query->where('sold_out', false),
                        blank: fn(Builder $query) => $query,
                    ),
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
            'index' => Pages\ListTicketPrices::route('/'),
            'create' => Pages\CreateTicketPrice::route('/create'),
            'edit' => Pages\EditTicketPrice::route('/{record}/edit'),
        ];
    }

    // Pour n'afficher que les prix des billets du salon courant
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Si nous sommes dans un contexte multi-tenant
        if (Filament::hasTenancy()) {
            return $query->where('salon_id', Filament::getTenant()->id);
        }

        return $query;
    }
}
