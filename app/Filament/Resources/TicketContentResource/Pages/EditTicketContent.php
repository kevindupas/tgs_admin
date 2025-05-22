<?php

namespace App\Filament\Resources\TicketContentResource\Pages;

use App\Filament\Resources\TicketContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketContent extends EditRecord
{
    protected static string $resource = TicketContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Modifier le contenu de ticket : ' . $this->record->name;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
