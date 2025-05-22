<?php

namespace App\Filament\Resources\TicketContentResource\Pages;

use App\Filament\Resources\TicketContentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicketContent extends CreateRecord
{
    protected static string $resource = TicketContentResource::class;

    public function getTitle(): string
    {
        return 'Créer un contenu de ticket';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
