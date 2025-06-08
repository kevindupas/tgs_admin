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
                                    ->default(fn($record) => $record?->id), // Valeur par défaut en édition

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

                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->height(60)
                    ->getStateUsing(function ($record) {
                        if (!$record->featured_image) {
                            return null;
                        }

                        // Si c'est juste un ID, récupérer l'objet Media et son URL
                        if (is_numeric($record->featured_image)) {
                            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($record->featured_image);
                            return $media ? $media->getUrl('thumb') : null;
                        }

                        // Si c'est déjà une URL ou un chemin, le retourner tel quel
                        return $record->featured_image;
                    })
                    ->defaultImageUrl(null),

                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category_name')
                    ->label('Catégorie')
                    ->getStateUsing(function ($record) use ($salon) {
                        $salonRelation = $record->salons->where('id', $salon->id)->first();
                        if (!$salonRelation || !$salonRelation->pivot->category_id) {
                            return 'Aucune';
                        }
                        $category = Categorie::find($salonRelation->pivot->category_id);
                        return $category ? $category->name : 'Inconnue';
                    }),

                Tables\Columns\TextColumn::make('availability_name')
                    ->label('Disponibilité')
                    ->getStateUsing(function ($record) use ($salon) {
                        $salonRelation = $record->salons->where('id', $salon->id)->first();
                        if (!$salonRelation || !$salonRelation->pivot->availability_id) {
                            return 'Aucune';
                        }
                        $availability = Availability::find($salonRelation->pivot->availability_id);
                        return $availability ? $availability->name : 'Inconnue';
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('En avant')
                    ->boolean()
                    ->getStateUsing(function ($record) use ($salon) {
                        $salonRelation = $record->salons->where('id', $salon->id)->first();
                        return $salonRelation ? $salonRelation->pivot->is_featured : false;
                    }),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publié')
                    ->boolean()
                    ->getStateUsing(function ($record) use ($salon) {
                        $salonRelation = $record->salons->where('id', $salon->id)->first();
                        return $salonRelation ? $salonRelation->pivot->is_published : false;
                    }),

                Tables\Columns\IconColumn::make('is_scheduled')
                    ->label('Programmé')
                    ->boolean()
                    ->getStateUsing(function ($record) use ($salon) {
                        $salonRelation = $record->salons->where('id', $salon->id)->first();
                        return $salonRelation ? $salonRelation->pivot->is_scheduled : false;
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Date de publication')
                    ->dateTime()
                    ->getStateUsing(function ($record) use ($salon) {
                        $salonRelation = $record->salons->where('id', $salon->id)->first();
                        return $salonRelation ? $salonRelation->pivot->published_at : null;
                    }),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('Ordre')
                    ->getStateUsing(function ($record) use ($salon) {
                        $salonRelation = $record->salons->where('id', $salon->id)->first();
                        return $salonRelation ? $salonRelation->pivot->display_order : 0;
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')
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
                Tables\Filters\TernaryFilter::make('is_scheduled')
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
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Catégorie')
                    ->options(function () use ($salon) {
                        return Categorie::where('salon_id', $salon->id)->pluck('name', 'id')->toArray();
                    })
                    ->query(function (Builder $query, array $data) use ($salon) {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return $query->whereHas('salons', function ($q) use ($salon, $data) {
                            $q->where('salon_id', $salon->id)->where('category_id', $data['value']);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->fillForm(function (Model $record) use ($salon): array {
                        // Récupérer les données pivot pour pré-remplir le formulaire
                        $salonRelation = $record->salons()->where('salon_id', $salon->id)->first();

                        if (!$salonRelation) {
                            return [];
                        }

                        $pivot = $salonRelation->pivot;

                        return [
                            'category_id' => $pivot->category_id,
                            'availability_id' => $pivot->availability_id,
                            'is_featured' => (bool) $pivot->is_featured,
                            'is_published' => (bool) $pivot->is_published,
                            'published_at' => $pivot->published_at,
                            'is_scheduled' => (bool) $pivot->is_scheduled,
                            'is_cancelled' => (bool) $pivot->is_cancelled,
                            'schedule_content' => $pivot->schedule_content,
                            'display_order' => (int) $pivot->display_order,
                        ];
                    })
                    ->action(function (Model $record, array $data) use ($salon): void {
                        // Mettre à jour uniquement les données pivot
                        $pivotData = collect($data)->only([
                            'category_id',
                            'availability_id',
                            'is_featured',
                            'is_published',
                            'published_at',
                            'is_scheduled',
                            'is_cancelled',
                            'schedule_content',
                            'display_order',
                        ])->filter(function ($value) {
                            return $value !== null;
                        })->toArray();

                        // Convertir les booléens en entiers pour la base de données
                        if (isset($pivotData['is_featured'])) {
                            $pivotData['is_featured'] = (int) $pivotData['is_featured'];
                        }
                        if (isset($pivotData['is_published'])) {
                            $pivotData['is_published'] = (int) $pivotData['is_published'];
                        }
                        if (isset($pivotData['is_scheduled'])) {
                            $pivotData['is_scheduled'] = (int) $pivotData['is_scheduled'];
                        }
                        if (isset($pivotData['is_cancelled'])) {
                            $pivotData['is_cancelled'] = (int) $pivotData['is_cancelled'];
                        }

                        $record->salons()->updateExistingPivot($salon->id, $pivotData);

                        // Notification de succès
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Article mis à jour')
                            ->body('Les modifications ont été sauvegardées avec succès.')
                            ->send();
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
