<?php

namespace App\Filament\Resources\SalonArticleResource\Pages;

use App\Filament\Resources\SalonArticleResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListSalonArticles extends ListRecords
{
    protected static string $resource = SalonArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajouter un article au salon')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }

    public function getTitle(): string
    {

        $salon = Filament::getTenant();

        if (!$salon) {
            return 'Liste des articles du salon';
        }

        return 'Liste des articles du salon : ' . $salon->name;
    }
}
