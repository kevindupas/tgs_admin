<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketContentResource\Pages;
use App\Models\TicketContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketContentResource extends Resource
{
    protected static ?string $model = TicketContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Contenu des billets';

    protected static ?string $recordTitleAttribute = 'name';

    // Important : ne pas scoper aux tenants car c'est global
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
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
            'index' => Pages\ListTicketContents::route('/'),
            'create' => Pages\CreateTicketContent::route('/create'),
            'edit' => Pages\EditTicketContent::route('/{record}/edit'),
        ];
    }

    // Très important : s'assurer que ce resource est visible depuis n'importe quel tenant
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
