<?php

namespace App\Filament\Resources\SalonArticleResource\Pages;

use App\Filament\Resources\SalonArticleResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSalonArticle extends CreateRecord
{
    protected static string $resource = SalonArticleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $salon = Filament::getTenant();

        // Récupérer l'article existant
        $article = \App\Models\Article::find($data['article_id']);

        // Vérifier si l'article existe déjà dans ce salon
        if ($article->salons()->where('salon_id', $salon->id)->exists()) {
            $this->notification()->danger('Cet article est déjà associé à ce salon.');
            $this->halt();
        }

        // Extraire les données pour la pivot
        $pivotData = collect($data)->except(['article_id'])->toArray();

        // Associer l'article au salon avec les données pivot
        $article->salons()->attach($salon->id, $pivotData);

        return $article;
    }

    public function getTitle(): string
    {
        // Récupérer le salon actuel
        $salon = Filament::getTenant();

        if (!$salon) {
            return 'Ajouter un article pour le salon';
        }

        return 'Ajouter un article pour le salon : ' . $salon->name;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
