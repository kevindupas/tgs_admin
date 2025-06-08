<?php

namespace App\Filament\Resources\SalonArticleResource\Pages;

use App\Filament\Resources\SalonArticleResource;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSalonArticle extends CreateRecord
{
    protected static string $resource = SalonArticleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $salon = Filament::getTenant();

        if (!$salon) {
            $this->halt();
        }

        // Récupérer l'article existant
        $article = \App\Models\Article::find($data['article_id']);

        if (!$article) {
            Notification::make()
                ->danger()
                ->title('Erreur')
                ->body('Article introuvable.')
                ->send();
            $this->halt();
        }

        // Vérifier si l'article existe déjà dans ce salon
        if ($article->salons()->where('salon_id', $salon->id)->exists()) {
            Notification::make()
                ->danger()
                ->title('Erreur')
                ->body('Cet article est déjà associé à ce salon.')
                ->send();
            $this->halt();
        }

        // Préparer les données pour la pivot
        $pivotData = collect($data)->except(['article_id'])->filter(function ($value) {
            return $value !== null && $value !== '';
        })->toArray();

        // Ajouter des valeurs par défaut si nécessaires
        $pivotData = array_merge([
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now(),
            'is_scheduled' => false,
            'is_cancelled' => false,
            'display_order' => 0,
        ], $pivotData);

        // Associer l'article au salon avec les données pivot
        $article->salons()->attach($salon->id, $pivotData);

        Notification::make()
            ->success()
            ->title('Succès')
            ->body('Article ajouté au salon avec succès.')
            ->send();

        return $article;
    }

    public function getTitle(): string
    {
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

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Article ajouté au salon';
    }
}
