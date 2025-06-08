<?php

namespace App\Filament\Pages;

use App\Models\BecomeAStaff;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use FilamentTiptapEditor\TiptapEditor;

class BecomeAStaffPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Pages spéciales';

    protected static ?string $navigationLabel = 'Devenir Staff';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.become-a-staff-page';

    public ?array $data = [];

    public function mount(): void
    {
        $salon = Filament::getTenant();

        // Récupérer ou créer le contenu BecomeAStaff pour ce salon
        $becomeAStaff = BecomeAStaff::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'title' => 'Devenir Staff',
                'content' => '<p>Informations pour devenir staff...</p>',
                'salon_id' => $salon->id,
            ]
        );

        $this->form->fill([
            'title' => $becomeAStaff->title,
            'content' => $becomeAStaff->content,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Devenir Staff')
                    ->description('Configurez le contenu de votre page Devenir Staff')
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

        // Récupérer ou créer le contenu BecomeAStaff directement
        $becomeAStaff = BecomeAStaff::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'title' => 'Devenir Staff',
                'content' => '<p>Informations pour devenir staff...</p>',
                'salon_id' => $salon->id,
            ]
        );

        $becomeAStaff->update($data);

        Notification::make()
            ->success()
            ->title('Page Devenir Staff sauvegardée')
            ->body('Le contenu de la page Devenir Staff a été mis à jour avec succès.')
            ->send();
    }

    public function getTitle(): string
    {
        return 'Page Devenir Staff';
    }

    // Afficher seulement si show_become_a_staff est activé pour le salon
    public static function shouldRegisterNavigation(): bool
    {
        try {
            $salon = Filament::getTenant();
            return $salon && $salon->show_become_a_staff;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
