<?php

namespace App\Filament\Resources\SalonArticleResource\Pages;

use App\Filament\Resources\SalonArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalonArticle extends EditRecord
{
    protected static string $resource = SalonArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Retirer du salon')
                ->modalDescription('Voulez-vous vraiment retirer cet article de ce salon ? L\'article ne sera pas supprimé définitivement, il sera simplement détaché de ce salon.'),
        ];
    }

    public function getTitle(): string
    {
        return 'Modifier l\'article : ' . $this->record->title;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
