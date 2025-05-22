<?php

namespace App\Filament\Resources\CategorieResource\Pages;

use App\Filament\Resources\CategorieResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategorie extends CreateRecord
{
    protected static string $resource = CategorieResource::class;

    public function getTitle(): string
    {
        return 'Créer une catégorie';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
