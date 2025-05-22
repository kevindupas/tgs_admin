<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Créer un filtre')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }

    public function getTitle(): string
    {
        return 'Liste des filtres';
    }
}
