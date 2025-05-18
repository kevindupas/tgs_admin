<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PracticalInfoResource\Pages;
use App\Models\PracticalInfo;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;

class PracticalInfoResource extends Resource
{
    protected static ?string $model = PracticalInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->label('Ordre d\'affichage')
                    ->integer()
                    ->default(0)
                    ->required()
                    ->helperText('Plus le nombre est petit, plus l\'info apparaîtra en premier (0 = premier)'),
                Forms\Components\ColorPicker::make('color')
                    ->label('Couleur')
                    ->nullable(),
                MediaPicker::make('icon')
                    ->label('Image')
                    ->nullable(),
                Forms\Components\Textarea::make('mini_content')
                    ->label('Aperçu du contenu')
                    ->required()
                    ->rows(3),
                TiptapEditor::make('content')
                    ->label('Contenu complet')
                    ->required(),
                MediaPicker::make('image')
                    ->label('Image')
                    ->nullable(),
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
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordre')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\IconColumn::make('icon')
                    ->label('Icône'),
                Tables\Columns\ColorColumn::make('color')
                    ->label('Couleur'),
                Tables\Columns\TextColumn::make('mini_content')
                    ->label('Aperçu')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('order')
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
            'index' => Pages\ListPracticalInfos::route('/'),
            'create' => Pages\CreatePracticalInfo::route('/create'),
            'edit' => Pages\EditPracticalInfo::route('/{record}/edit'),
        ];
    }

    // Pour n'afficher que les infos pratiques du salon courant
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
