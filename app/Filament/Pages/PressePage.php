<?php

namespace App\Filament\Pages;

use App\Models\Presse;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use FilamentTiptapEditor\TiptapEditor;

class PressePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Pages spéciales';

    protected static ?string $navigationLabel = 'Presse';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.presse-page';

    public ?array $data = [];

    public function mount(): void
    {
        $salon = Filament::getTenant();

        // Récupérer ou créer le contenu Presse pour ce salon
        $presse = Presse::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'first_title' => 'Section Presse',
                'first_content' => '<p>Contenu de la section presse...</p>',
                'second_title' => 'Deuxième section',
                'second_content' => '<p>Contenu de la deuxième section...</p>',
                'third_title' => 'Troisième section',
                'third_content' => '<p>Contenu de la troisième section...</p>',
                'salon_id' => $salon->id,
            ]
        );

        $this->form->fill([
            'ticket_presse_link' => $presse->ticket_presse_link,
            'first_title' => $presse->first_title,
            'first_content' => $presse->first_content,
            'second_title' => $presse->second_title,
            'second_content' => $presse->second_content,
            'second_ticket' => $presse->second_ticket,
            'third_title' => $presse->third_title,
            'third_content' => $presse->third_content,
            'third_ticket' => $presse->third_ticket,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Presse')
                    ->description('Configurez le contenu de votre page Presse')
                    ->schema([
                        Forms\Components\TextInput::make('ticket_presse_link')
                            ->label('Lien ticket presse')
                            ->url()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('first_title')
                            ->label('Premier titre')
                            ->required()
                            ->maxLength(255),

                        TiptapEditor::make('first_content')
                            ->label('Premier contenu')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('second_title')
                            ->label('Deuxième titre')
                            ->required()
                            ->maxLength(255),

                        TiptapEditor::make('second_content')
                            ->label('Deuxième contenu')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('second_ticket')
                            ->label('Deuxième lien ticket')
                            ->url(),

                        Forms\Components\TextInput::make('third_title')
                            ->label('Troisième titre')
                            ->required()
                            ->maxLength(255),

                        TiptapEditor::make('third_content')
                            ->label('Troisième contenu')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('third_ticket')
                            ->label('Troisième lien ticket')
                            ->url(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $salon = Filament::getTenant();

        // Récupérer ou créer le contenu Presse directement
        $presse = Presse::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'first_title' => 'Section Presse',
                'first_content' => '<p>Contenu de la section presse...</p>',
                'second_title' => 'Deuxième section',
                'second_content' => '<p>Contenu de la deuxième section...</p>',
                'third_title' => 'Troisième section',
                'third_content' => '<p>Contenu de la troisième section...</p>',
                'salon_id' => $salon->id,
            ]
        );

        $presse->update($data);

        Notification::make()
            ->success()
            ->title('Page Presse sauvegardée')
            ->body('Le contenu de la page Presse a été mis à jour avec succès.')
            ->send();
    }

    public function getTitle(): string
    {
        return 'Page Presse';
    }

    // Afficher seulement si show_presses est activé pour le salon
    public static function shouldRegisterNavigation(): bool
    {
        try {
            $salon = Filament::getTenant();
            return $salon && $salon->show_presses;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
