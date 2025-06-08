<?php

namespace App\Filament\Resources\E2cArticleResource\Pages;

use App\Filament\Resources\E2cArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditE2cArticle extends EditRecord
{
    protected static string $resource = E2cArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        $type = $this->record->is_jury ? 'jury' : 'participant';
        return 'Modifier le ' . $type . ' : ' . $this->record->title;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
