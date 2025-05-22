<?php

namespace App\Filament\Resources\TicketPriceResource\Pages;

use App\Filament\Resources\TicketPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicketPrice extends CreateRecord
{
    protected static string $resource = TicketPriceResource::class;

    public function getTitle(): string
    {
        return 'Créer un billet';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
