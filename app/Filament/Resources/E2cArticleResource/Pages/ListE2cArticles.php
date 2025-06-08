<?php

namespace App\Filament\Resources\E2cArticleResource\Pages;

use App\Filament\Resources\E2cArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListE2cArticles extends ListRecords
{
    protected static string $resource = E2cArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajouter un jury/participant')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }

    public function getTitle(): string
    {
        return 'Jury & Participants E2C';
    }
}
