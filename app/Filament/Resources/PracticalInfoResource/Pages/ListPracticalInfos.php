<?php

namespace App\Filament\Resources\PracticalInfoResource\Pages;

use App\Filament\Resources\PracticalInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPracticalInfos extends ListRecords
{
    protected static string $resource = PracticalInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Créer une information pratique')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }

    public function getTitle(): string
    {
        return 'Liste des informations pratiques';
    }
}
