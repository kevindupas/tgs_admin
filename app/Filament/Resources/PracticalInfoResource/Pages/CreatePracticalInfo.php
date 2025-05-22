<?php

namespace App\Filament\Resources\PracticalInfoResource\Pages;

use App\Filament\Resources\PracticalInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePracticalInfo extends CreateRecord
{
    protected static string $resource = PracticalInfoResource::class;

    public function getTitle(): string
    {
        return 'Créer une information pratique';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
