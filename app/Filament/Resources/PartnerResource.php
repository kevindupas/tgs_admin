<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Gestion du salon';

    protected static ?string $navigationLabel = 'Partenaires';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('sponsor')
                    ->label('Sponsor officiel')
                    ->default(false),
                MediaPicker::make('logo')
                    ->label('Logo')
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                Tables\Columns\IconColumn::make('sponsor')
                    ->label('Sponsor')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('sponsor')
                    ->label('Sponsors')
                    ->placeholder('Tous')
                    ->trueLabel('Sponsors uniquement')
                    ->falseLabel('Non-sponsors uniquement')
                    ->queries(
                        true: fn(Builder $query) => $query->where('sponsor', true),
                        false: fn(Builder $query) => $query->where('sponsor', false),
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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }

    // Pour n'afficher que les partenaires du salon courant
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
