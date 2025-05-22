<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AvailabilityResource\Pages;
use App\Models\Availability;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AvailabilityResource extends Resource
{
    protected static ?string $model = Availability::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Gestion du salon';

    protected static ?string $navigationLabel = 'Disponibilités';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Disponibilité';
    protected static ?string $pluralLabel = 'Disponibilités';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
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
            'index' => Pages\ListAvailabilities::route('/'),
            'create' => Pages\CreateAvailability::route('/create'),
            'edit' => Pages\EditAvailability::route('/{record}/edit'),
        ];
    }

    // Pour n'afficher que les disponibilités du salon courant
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
