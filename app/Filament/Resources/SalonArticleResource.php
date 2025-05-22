<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalonArticleResource\Pages;
use App\Models\Article;
use App\Models\Availability;
use App\Models\Categorie;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SalonArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Gestion du salon';

    protected static ?string $navigationLabel = 'Articles du salon';

    protected static ?string $pluralLabel = 'Articles du salon';

    protected static ?string $modelLabel = 'Article du salon';

    protected static ?string $slug = 'salon-articles';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $salon = Filament::getTenant();

        // Récupérer les catégories et disponibilités du salon actuel
        $categories = Categorie::where('salon_id', $salon->id)->pluck('name', 'id')->toArray();
        $availabilities = Availability::where('salon_id', $salon->id)->pluck('name', 'id')->toArray();

        return $form
            ->schema([
                Forms\Components\Tabs::make('Salon Article')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Article')
                            ->schema([
                                Forms\Components\Select::make('article_id')
                                    ->label('Sélectionner un article')
                                    ->options(Article::all()->pluck('title', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->disabled(fn($context) => $context === 'edit')
                                    ->hiddenOn('edit'),

                                Forms\Components\Placeholder::make('article_info')
                                    ->label('Article')
                                    ->content(fn($record) => $record?->title ?? 'Aucun article sélectionné')
                                    ->visibleOn('edit'),

                                Forms\Components\Select::make('category_id')
                                    ->label('Catégorie')
                                    ->options($categories)
                                    ->searchable(),

                                Forms\Components\Select::make('availability_id')
                                    ->label('Disponibilité')
                                    ->options($availabilities)
                                    ->searchable(),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Mettre en avant dans ce salon')
                                    ->default(false),

                                Forms\Components\Toggle::make('is_published')
                                    ->label('Publié')
                                    ->default(true),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Date de publication')
                                    ->default(now()),

                                Forms\Components\TextInput::make('display_order')
                                    ->label('Ordre d\'affichage')
                                    ->integer()
                                    ->default(0),
                            ]),

                        Forms\Components\Tabs\Tab::make('Planning')
                            ->schema([
                                Forms\Components\Toggle::make('is_scheduled')
                                    ->label('Planifier dans le salon')
                                    ->default(false)
                                    ->live(),

                                TiptapEditor::make('schedule_content')
                                    ->label('Contenu du planning')
                                    ->helperText('Contenu à afficher dans le planning')
                                    ->columnSpanFull()
                                    ->visible(fn(Forms\Get $get) => $get('is_scheduled')),

                                Forms\Components\Toggle::make('is_cancelled')
                                    ->label('Annulé')
                                    ->default(false)
                                    ->visible(fn(Forms\Get $get) => $get('is_scheduled')),
                            ]),

                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $salon = Filament::getTenant();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.category_id')
                    ->label('Catégorie')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'Aucune';
                        $category = Categorie::find($state);
                        return $category ? $category->name : 'Inconnue';
                    }),
                Tables\Columns\IconColumn::make('pivot.is_featured')
                    ->label('En avant')
                    ->boolean(),
                Tables\Columns\IconColumn::make('pivot.is_scheduled')
                    ->label('Programmé')
                    ->boolean(),
                Tables\Columns\TextColumn::make('pivot.start_datetime')
                    ->label('Début')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.location')
                    ->label('Lieu'),
                Tables\Columns\TextColumn::make('pivot.day')
                    ->label('Jour'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('pivot.is_featured')
                    ->label('En avant')
                    ->queries(
                        true: fn(Builder $query) => $query->whereHas('salons', function ($q) use ($salon) {
                            $q->where('salon_id', $salon->id)->where('is_featured', true);
                        }),
                        false: fn(Builder $query) => $query->whereHas('salons', function ($q) use ($salon) {
                            $q->where('salon_id', $salon->id)->where('is_featured', false);
                        }),
                        blank: fn(Builder $query) => $query,
                    ),
                Tables\Filters\TernaryFilter::make('pivot.is_scheduled')
                    ->label('Programmé')
                    ->queries(
                        true: fn(Builder $query) => $query->whereHas('salons', function ($q) use ($salon) {
                            $q->where('salon_id', $salon->id)->where('is_scheduled', true);
                        }),
                        false: fn(Builder $query) => $query->whereHas('salons', function ($q) use ($salon) {
                            $q->where('salon_id', $salon->id)->where('is_scheduled', false);
                        }),
                        blank: fn(Builder $query) => $query,
                    ),
                Tables\Filters\SelectFilter::make('pivot.day')
                    ->label('Jour')
                    ->options([
                        'J1' => 'Jour 1',
                        'J2' => 'Jour 2',
                        'J3' => 'Jour 3',
                        'Tous' => 'Tous les jours',
                    ])
                    ->query(function (Builder $query, array $data) use ($salon) {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return $query->whereHas('salons', function ($q) use ($salon, $data) {
                            $q->where('salon_id', $salon->id)->where('day', $data['value']);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data, Model $record) use ($salon) {
                        // Récupérer les données pivot
                        $pivotRecord = $record->salons()->where('salon_id', $salon->id)->first();

                        if (!$pivotRecord) {
                            return $data;
                        }

                        $pivot = $pivotRecord->pivot;

                        // Fusionner les données pivot avec les données de l'article
                        foreach ($pivot->getAttributes() as $key => $value) {
                            if (!in_array($key, ['id', 'article_id', 'salon_id', 'created_at', 'updated_at'])) {
                                $data[$key] = $value;
                            }
                        }

                        return $data;
                    })
                    ->using(function (Model $record, array $data) use ($salon) {
                        // Mettre à jour uniquement les données pivot
                        $pivotData = collect($data)->except(['id', 'title', 'slug', 'content', 'featured_image', 'gallery', 'videos', 'social_links'])->toArray();

                        $record->salons()->updateExistingPivot($salon->id, $pivotData);

                        return $record;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Voulez-vous vraiment retirer cet article de ce salon ? L\'article ne sera pas supprimé définitivement, il sera simplement détaché de ce salon.')
                    ->using(function (Model $record) use ($salon) {
                        // Détacher uniquement de ce salon
                        $record->salons()->detach($salon->id);

                        return true;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Voulez-vous vraiment retirer ces articles de ce salon ? Les articles ne seront pas supprimés définitivement, ils seront simplement détachés de ce salon.')
                        ->action(function ($records) use ($salon) {
                            $salonId = $salon->id;
                            foreach ($records as $record) {
                                $record->salons()->detach($salonId);
                            }
                        }),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $salon = Filament::getTenant();

        return parent::getEloquentQuery()
            ->whereHas('salons', function ($query) use ($salon) {
                $query->where('salon_id', $salon->id);
            })
            ->with(['salons' => function ($query) use ($salon) {
                $query->where('salon_id', $salon->id);
            }]);
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
            'index' => Pages\ListSalonArticles::route('/'),
            'create' => Pages\CreateSalonArticle::route('/create'),
            'edit' => Pages\EditSalonArticle::route('/{record}/edit'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        $salon = Filament::getTenant();
        return $record->salons()->where('salon_id', $salon->id)->exists();
    }
}
