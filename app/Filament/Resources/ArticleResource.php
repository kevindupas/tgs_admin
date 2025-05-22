<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Str;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Contenu global';

    protected static ?string $navigationLabel = 'Articles (Global)';

    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $slug = 'articles';
    protected static ?string $modelLabel = 'Liste des articles';
    protected static ?string $pluralModelLabel = 'Liste des articles';

    protected static ?int $navigationSort = 1;

    // Ne pas scoper aux tenants car c'est global
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Articles')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informations générales')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Nom')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($set, $state) {
                                        $set('slug', Str::slug($state));
                                    }),

                                MediaPicker::make('featured_image')
                                    ->label('Image à la une'),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->hidden()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),

                        Forms\Components\Tabs\Tab::make('Contenu')
                            ->schema([
                                TiptapEditor::make('content')
                                    ->label('Contenu de l\'article')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Galerie')
                            ->schema([
                                MediaPicker::make('gallery')
                                    ->label('Images')
                                    ->multiple()
                                    ->reorderable()
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Vidéos')
                            ->schema([
                                Forms\Components\Repeater::make('videos')
                                    ->label('Liens vidéos / YouTube')
                                    ->schema([
                                        Forms\Components\TextInput::make('url')
                                            ->label('URL de la vidéo')
                                            ->helperText('URL YouTube, Vimeo, etc.')
                                            ->url(),
                                    ])
                                    ->reorderableWithDragAndDrop()
                                    ->collapsible()
                                    ->itemLabel(fn(array $state): string => $state['url'] ?? 'Vidéo')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Réseaux sociaux')
                            ->schema([
                                Forms\Components\Section::make('Réseaux sociaux')
                                    ->schema([
                                        Forms\Components\TextInput::make('social_links.facebook')
                                            ->label('Facebook')
                                            ->url()
                                            ->prefix('https://')
                                            ->helperText('Ex: facebook.com/username'),

                                        Forms\Components\TextInput::make('social_links.instagram')
                                            ->label('Instagram')
                                            ->url()
                                            ->prefix('https://')
                                            ->helperText('Ex: instagram.com/username'),

                                        Forms\Components\TextInput::make('social_links.twitter')
                                            ->label('Twitter')
                                            ->url()
                                            ->prefix('https://')
                                            ->helperText('Ex: twitter.com/username'),

                                        Forms\Components\TextInput::make('social_links.youtube')
                                            ->label('YouTube')
                                            ->url()
                                            ->prefix('https://')
                                            ->helperText('Ex: youtube.com/channel/ID'),

                                        Forms\Components\TextInput::make('social_links.tiktok')
                                            ->label('TikTok')
                                            ->url()
                                            ->prefix('https://')
                                            ->helperText('Ex: tiktok.com/@username'),

                                        Forms\Components\TextInput::make('social_links.website')
                                            ->label('Site web')
                                            ->url()
                                            ->prefix('https://')
                                            ->helperText('Site web officiel'),

                                        Forms\Components\TextInput::make('social_links.wikipedia')
                                            ->label('Wikipedia')
                                            ->url()
                                            ->prefix('https://')
                                            ->helperText('Ex: wikipedia.org/wiki/Nom'),

                                        Forms\Components\TextInput::make('social_links.twitch')
                                            ->label('Twitch')
                                            ->url()
                                            ->prefix('https://')
                                            ->helperText('Ex: twitch.tv/username'),
                                    ])
                                    ->columns(2),
                            ]),


                        Forms\Components\Tabs\Tab::make('Salons associés')
                            ->schema([
                                Forms\Components\Placeholder::make('salons_info')
                                    ->label('Salons utilisant cet article')
                                    ->content(function ($record) {
                                        if (!$record || !$record->exists) {
                                            return 'Vous pourrez associer cet article à des salons après l\'avoir créé.';
                                        }

                                        $salons = $record->salons;

                                        if ($salons->isEmpty()) {
                                            return 'Cet article n\'est associé à aucun salon pour le moment.';
                                        }

                                        return implode(', ', $salons->pluck('name')->toArray());
                                    }),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),

                // Affichage de l'image avec gestion de l'URL
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->height(70)
                    ->getStateUsing(function (Article $record) {
                        if (!$record->featured_image) {
                            return null;
                        }

                        // Si c'est juste un ID, récupérer l'objet Media et son URL
                        if (is_numeric($record->featured_image)) {
                            $media = Media::find($record->featured_image);
                            return $media ? $media->getUrl() : null;
                        }

                        // Si c'est déjà une URL ou un chemin, le retourner tel quel
                        return $record->featured_image;
                    }),

                // Affichage des salons sous forme de badges
                Tables\Columns\TextColumn::make('salons')
                    ->label('Salons')
                    ->getStateUsing(function (Article $record) {
                        if ($record->salons->isEmpty()) {
                            return [];
                        }
                        return $record->salons->pluck('name')->toArray();
                    })
                    ->badge()
                    ->separator(' ')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalDescription('Attention : supprimer cet article le retirera aussi de tous les salons associés.')
                    ->modalSubmitActionLabel('Oui, supprimer définitivement')
                    ->before(function ($record) {
                        // Détacher l'article de tous les salons avant suppression
                        // (cela préserve les données des salons mais retire la relation)
                        if ($record->salons()->count() > 0) {
                            $record->salons()->detach();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalDescription('Attention : supprimer ces articles les retirera aussi de tous les salons associés.')
                        ->modalSubmitActionLabel('Oui, supprimer définitivement')
                        ->before(function ($records) {
                            // Détacher les articles des salons avant suppression
                            foreach ($records as $record) {
                                $record->salons()->detach();
                            }
                        }),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    // Très important : s'assurer que ce resource est visible depuis n'importe quel tenant
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
