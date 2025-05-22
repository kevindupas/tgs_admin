<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Gestion du salon';

    protected static ?string $navigationLabel = 'FAQs';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Question')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->label('Ordre d\'affichage')
                    ->integer()
                    ->default(0)
                    ->required()
                    ->helperText('Plus le nombre est petit, plus la FAQ apparaîtra en premier (0 = premier)'),
                Forms\Components\Textarea::make('mini_content')
                    ->label('Aperçu de la réponse')
                    ->required()
                    ->rows(3),
                TiptapEditor::make('content')
                    ->label('Réponse complète')
                    ->required(),
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
                    ->label('Question')
                    ->searchable(),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }

    // Pour n'afficher que les FAQ du salon courant
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
