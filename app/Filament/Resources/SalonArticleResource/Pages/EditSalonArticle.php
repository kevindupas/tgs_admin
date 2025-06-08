<?php

namespace App\Filament\Resources\SalonArticleResource\Pages;

use App\Filament\Resources\SalonArticleResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSalonArticle extends EditRecord
{
    protected static string $resource = SalonArticleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $salon = Filament::getTenant();

        if (!$salon) {
            return $data;
        }

        // Récupérer les données pivot pour ce salon
        $salonRelation = $this->record->salons()->where('salon_id', $salon->id)->first();

        if (!$salonRelation) {
            return array_merge($data, ['article_id' => $this->record->id]);
        }

        $pivot = $salonRelation->pivot;

        // Fusionner les données pivot avec les données de l'article
        return array_merge($data, [
            'article_id' => $this->record->id, // L'article actuellement sélectionné
            'category_id' => $pivot->category_id,
            'availability_id' => $pivot->availability_id,
            'is_featured' => (bool) $pivot->is_featured,
            'is_published' => (bool) $pivot->is_published,
            'published_at' => $pivot->published_at,
            'is_scheduled' => (bool) $pivot->is_scheduled,
            'is_cancelled' => (bool) $pivot->is_cancelled,
            'schedule_content' => $pivot->schedule_content,
            'display_order' => (int) $pivot->display_order,
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $salon = Filament::getTenant();

        if (!$salon) {
            return [];
        }

        // Garder les données pivot + article_id pour la sauvegarde
        return collect($data)->only([
            'article_id',
            'category_id',
            'availability_id',
            'is_featured',
            'is_published',
            'published_at',
            'is_scheduled',
            'is_cancelled',
            'schedule_content',
            'display_order',
        ])->filter(function ($value) {
            return $value !== null;
        })->toArray();
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $salon = Filament::getTenant();

        if (!$salon) {
            $this->halt();
        }

        $newArticleId = $data['article_id'];
        $currentArticleId = $record->id;

        // Préparer les données pivot (sans article_id)
        $pivotData = collect($data)->except(['article_id'])->toArray();

        // Convertir les booléens en entiers pour la base de données
        if (isset($pivotData['is_featured'])) {
            $pivotData['is_featured'] = (int) $pivotData['is_featured'];
        }
        if (isset($pivotData['is_published'])) {
            $pivotData['is_published'] = (int) $pivotData['is_published'];
        }
        if (isset($pivotData['is_scheduled'])) {
            $pivotData['is_scheduled'] = (int) $pivotData['is_scheduled'];
        }
        if (isset($pivotData['is_cancelled'])) {
            $pivotData['is_cancelled'] = (int) $pivotData['is_cancelled'];
        }

        // Si l'article a changé
        if ($newArticleId != $currentArticleId) {
            // Vérifier que le nouvel article n'est pas déjà dans ce salon
            $newArticle = \App\Models\Article::find($newArticleId);
            if ($newArticle && $newArticle->salons()->where('salon_id', $salon->id)->exists()) {
                Notification::make()
                    ->danger()
                    ->title('Erreur')
                    ->body('Cet article est déjà associé à ce salon.')
                    ->send();
                $this->halt();
            }

            // Détacher l'ancien article
            $record->salons()->detach($salon->id);

            // Attacher le nouveau article
            if ($newArticle) {
                $newArticle->salons()->attach($salon->id, $pivotData);

                // Rediriger vers la liste car l'enregistrement a changé
                $this->redirect($this->getResource()::getUrl('index'));
            }
        } else {
            // Même article, juste mettre à jour les données pivot
            $record->salons()->updateExistingPivot($salon->id, $pivotData);
        }

        // Notification de succès
        Notification::make()
            ->success()
            ->title('Article mis à jour')
            ->body('Les modifications ont été sauvegardées avec succès.')
            ->send();

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Retirer du salon')
                ->modalDescription('Voulez-vous vraiment retirer cet article de ce salon ? L\'article ne sera pas supprimé définitivement, il sera simplement détaché de ce salon.')
                ->action(function () {
                    $salon = Filament::getTenant();

                    if ($salon) {
                        $this->record->salons()->detach($salon->id);

                        Notification::make()
                            ->success()
                            ->title('Article retiré')
                            ->body('L\'article a été retiré du salon avec succès.')
                            ->send();
                    }

                    return redirect($this->getResource()::getUrl('index'));
                }),
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

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Article mis à jour avec succès';
    }
}
