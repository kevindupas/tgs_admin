<?php

namespace App\Filament\Pages;

use App\Models\E2cContent;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use FilamentTiptapEditor\TiptapEditor;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;

class E2cContentPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'E2C';

    protected static ?string $navigationLabel = 'Contenu E2C';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.e2c-content-page';

    public ?array $data = [];

    public function mount(): void
    {
        $salon = Filament::getTenant();

        // Récupérer ou créer le contenu E2C pour ce salon
        $this->e2cContent = E2cContent::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'title' => 'Concours E2C',
                'text' => '<p>Découvrez notre concours E2C...</p>',
                'salon_id' => $salon->id,
            ]
        );

        $this->form->fill([
            'title' => $this->e2cContent->title,
            'text' => $this->e2cContent->text,
            'logo' => $this->e2cContent->logo,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contenu E2C')
                    ->description('Configurez le contenu principal de votre section E2C')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Titre de la section E2C')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Concours E2C 2024'),

                        MediaPicker::make('logo')
                            ->label('Logo E2C')
                            ->helperText('Logo spécifique pour la section E2C')
                            ->columnSpanFull()
                            ->extraAttributes([
                                'class' => 'media-picker-small',
                                'style' => 'max-width: 300px;'
                            ]),

                        TiptapEditor::make('text')
                            ->label('Texte de présentation')
                            ->required()
                            ->placeholder('Présentez votre concours E2C, les règles, les prix...')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Sauvegarder')
                ->color('primary')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $salon = Filament::getTenant();

        // Récupérer ou créer le contenu E2C directement
        $e2cContent = E2cContent::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'title' => 'Concours E2C',
                'text' => '<p>Découvrez notre concours E2C...</p>',
                'salon_id' => $salon->id,
            ]
        );

        $e2cContent->update($data);

        Notification::make()
            ->success()
            ->title('Contenu E2C sauvegardé')
            ->body('Le contenu E2C a été mis à jour avec succès.')
            ->send();
    }

    public function getTitle(): string
    {
        return 'Contenu E2C';
    }

    // Afficher seulement si E2C est activé pour le salon
    public static function shouldRegisterNavigation(): bool
    {
        try {
            $salon = Filament::getTenant();
            return $salon && $salon->e2c;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
