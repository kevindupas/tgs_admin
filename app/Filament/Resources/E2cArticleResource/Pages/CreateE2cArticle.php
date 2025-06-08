<?php

namespace App\Filament\Resources\E2cArticleResource\Pages;

use App\Filament\Resources\E2cArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateE2cArticle extends CreateRecord
{
    protected static string $resource = E2cArticleResource::class;

    public function getTitle(): string
    {
        return 'Ajouter un jury/participant E2C';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
