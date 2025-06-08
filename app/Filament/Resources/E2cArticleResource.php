<?php

namespace App\Filament\Resources;

use App\Filament\Resources\E2cArticleResource\Pages;
use App\Models\E2cArticle;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;

class E2cArticleResource extends Resource
{
    protected static ?string $model = E2cArticle::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'E2C';

    protected static ?string $navigationLabel = 'Jury & Participants';

    protected static ?string $modelLabel = 'Jury/Participant';
    protected static ?string $pluralModelLabel = 'Jury & Participants';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('E2C Article')
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

                                Forms\Components\Toggle::make('is_jury')
                                    ->label('Membre du jury')
                                    ->default(false)
                                    ->helperText('Activez si cette personne fait partie du jury'),

                                Forms\Components\TextInput::make('display_order')
                                    ->label('Ordre d\'affichage')
                                    ->integer()
                                    ->default(0)
                                    ->helperText('Plus le nombre est petit, plus l\'élément apparaîtra en premier'),

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
                                    ->label('Contenu de présentation')
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
                    ])
                    ->columnSpanFull(),

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
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->height(60)
                    ->getStateUsing(function (E2cArticle $record) {
                        if (!$record->featured_image) {
                            return null;
                        }

                        if (is_numeric($record->featured_image)) {
                            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($record->featured_image);
                            return $media ? $media->getUrl('thumb') : null;
                        }

                        return $record->featured_image;
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_jury')
                    ->label('Jury')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-user'),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('Ordre')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('display_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_jury')
                    ->label('Type')
                    ->placeholder('Tous')
                    ->trueLabel('Jury uniquement')
                    ->falseLabel('Participants uniquement')
                    ->queries(
                        true: fn(Builder $query) => $query->where('is_jury', true),
                        false: fn(Builder $query) => $query->where('is_jury', false),
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
            'index' => Pages\ListE2cArticles::route('/'),
            'create' => Pages\CreateE2cArticle::route('/create'),
            'edit' => Pages\EditE2cArticle::route('/{record}/edit'),
        ];
    }

    // Pour n'afficher que les articles E2C du salon courant
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Filament::hasTenancy()) {
            return $query->where('salon_id', Filament::getTenant()->id);
        }

        return $query;
    }

    // Afficher seulement si E2C est activé pour le salon
    public static function shouldRegisterNavigation(): bool
    {
        try {
            $salon = Filament::getTenant();
            return $salon && $salon->e2c;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
