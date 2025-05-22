<?php

namespace App\Filament\Resources\AvailabilityResource\Pages;

use App\Filament\Resources\AvailabilityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAvailability extends CreateRecord
{
    protected static string $resource = AvailabilityResource::class;

    public function getTitle(): string
    {
        return 'Créer un disponibilité';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
