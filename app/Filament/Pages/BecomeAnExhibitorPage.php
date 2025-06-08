<?php

namespace App\Filament\Pages;

use App\Models\BecomeAnExhibitor;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use FilamentTiptapEditor\TiptapEditor;

class BecomeAnExhibitorPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Pages spéciales';

    protected static ?string $navigationLabel = 'Devenir Exposant';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.become-an-exhibitor-page';

    public ?array $data = [];

    public function mount(): void
    {
        $salon = Filament::getTenant();

        // Récupérer ou créer le contenu BecomeAnExhibitor pour ce salon
        $becomeAnExhibitor = BecomeAnExhibitor::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'title' => 'Devenir Exposant',
                'content' => '<p>Informations pour devenir exposant...</p>',
                'salon_id' => $salon->id,
            ]
        );

        $this->form->fill([
            'title' => $becomeAnExhibitor->title,
            'content' => $becomeAnExhibitor->content,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Devenir Exposant')
                    ->description('Configurez le contenu de votre page Devenir Exposant')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Titre de la page')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TiptapEditor::make('content')
                            ->label('Contenu de la page')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $salon = Filament::getTenant();

        // Récupérer ou créer le contenu BecomeAnExhibitor directement
        $becomeAnExhibitor = BecomeAnExhibitor::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'title' => 'Devenir Exposant',
                'content' => '<p>Informations pour devenir exposant...</p>',
                'salon_id' => $salon->id,
            ]
        );

        $becomeAnExhibitor->update($data);

        Notification::make()
            ->success()
            ->title('Page Devenir Exposant sauvegardée')
            ->body('Le contenu de la page Devenir Exposant a été mis à jour avec succès.')
            ->send();
    }

    public function getTitle(): string
    {
        return 'Page Devenir Exposant';
    }

    // Afficher seulement si show_become_an_exhibitor est activé pour le salon
    public static function shouldRegisterNavigation(): bool
    {
        try {
            $salon = Filament::getTenant();
            return $salon && $salon->show_become_an_exhibitor;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
