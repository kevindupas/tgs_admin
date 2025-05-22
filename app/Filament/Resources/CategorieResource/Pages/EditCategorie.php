<?php

namespace App\Filament\Resources\CategorieResource\Pages;

use App\Filament\Resources\CategorieResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategorie extends EditRecord
{
    protected static string $resource = CategorieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Modifier la catégorie : ' . $this->record->name;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
