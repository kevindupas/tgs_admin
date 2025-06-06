<?php

namespace App\Filament\Resources\PracticalInfoResource\Pages;

use App\Filament\Resources\PracticalInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPracticalInfo extends EditRecord
{
    protected static string $resource = PracticalInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Modifier l\'information pratique : ' . $this->record->title;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
