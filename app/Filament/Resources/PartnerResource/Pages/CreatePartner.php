<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePartner extends CreateRecord
{
    protected static string $resource = PartnerResource::class;

    public function getTitle(): string
    {
        return 'Créer un partenaire';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
