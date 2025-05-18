<?php

namespace App\Filament\Resources\TicketPriceResource\Pages;

use App\Filament\Resources\TicketPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTicketPrices extends ListRecords
{
    protected static string $resource = TicketPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
