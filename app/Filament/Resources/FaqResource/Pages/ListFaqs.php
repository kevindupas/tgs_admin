<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaqs extends ListRecords
{
    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Créer une FAQ')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }

    public function getTitle(): string
    {
        return 'Liste des questions fréquentes';
    }
}
