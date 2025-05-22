<?php

namespace App\Filament\Resources\TicketPriceResource\Pages;

use App\Filament\Resources\TicketPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketPrice extends EditRecord
{
    protected static string $resource = TicketPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Modifier le billet : ' . $this->record->name;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
