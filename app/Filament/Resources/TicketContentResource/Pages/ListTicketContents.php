<?php

namespace App\Filament\Resources\TicketContentResource\Pages;

use App\Filament\Resources\TicketContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTicketContents extends ListRecords
{
    protected static string $resource = TicketContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Créer un contenu de ticket'),
        ];
    }

    public function getTitle(): string
    {
        return 'Liste des contenus de ticket';
    }
}
