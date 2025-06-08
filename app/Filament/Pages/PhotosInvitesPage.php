<?php

namespace App\Filament\Pages;

use App\Models\PhotosInvite;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use FilamentTiptapEditor\TiptapEditor;

class PhotosInvitesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationGroup = 'Pages spéciales';

    protected static ?string $navigationLabel = 'Photos Invités';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.photos-invites-page';

    public ?array $data = [];

    public function mount(): void
    {
        $salon = Filament::getTenant();

        // Récupérer ou créer le contenu Photos Invités pour ce salon
        $photosInvite = PhotosInvite::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'mini_title' => 'Photos',
                'title' => 'Photos Invités',
                'first_subtitle' => 'Première section',
                'first_content' => '<p>Contenu de la première section...</p>',
                'second_subtitle' => 'Deuxième section',
                'second_content' => '<p>Contenu de la deuxième section...</p>',
                'first_title_link' => 'Premier lien',
                'first_link' => '#',
                'second_title_link' => 'Deuxième lien',
                'second_link' => '#',
                'third_subtitle' => 'Troisième section',
                'salon_id' => $salon->id,
            ]
        );

        $this->form->fill([
            'mini_title' => $photosInvite->mini_title,
            'title' => $photosInvite->title,
            'first_subtitle' => $photosInvite->first_subtitle,
            'first_content' => $photosInvite->first_content,
            'second_subtitle' => $photosInvite->second_subtitle,
            'second_content' => $photosInvite->second_content,
            'first_title_link' => $photosInvite->first_title_link,
            'first_link' => $photosInvite->first_link,
            'second_title_link' => $photosInvite->second_title_link,
            'second_link' => $photosInvite->second_link,
            'third_subtitle' => $photosInvite->third_subtitle,
            'third_link' => $photosInvite->third_link,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Photos Invités')
                    ->description('Configurez le contenu de votre page Photos Invités')
                    ->schema([
                        Forms\Components\TextInput::make('mini_title')
                            ->label('Mini titre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('title')
                            ->label('Titre principal')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('first_subtitle')
                            ->label('Premier sous-titre')
                            ->required()
                            ->maxLength(255),

                        TiptapEditor::make('first_content')
                            ->label('Premier contenu')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('second_subtitle')
                            ->label('Deuxième sous-titre')
                            ->required()
                            ->maxLength(255),

                        TiptapEditor::make('second_content')
                            ->label('Deuxième contenu')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('first_title_link')
                            ->label('Titre du premier lien')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('first_link')
                            ->label('Premier lien')
                            ->required()
                            ->url(),

                        Forms\Components\TextInput::make('second_title_link')
                            ->label('Titre du deuxième lien')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('second_link')
                            ->label('Deuxième lien')
                            ->required()
                            ->url(),

                        Forms\Components\TextInput::make('third_subtitle')
                            ->label('Troisième sous-titre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('third_link')
                            ->label('Troisième lien')
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

        // Récupérer ou créer le contenu Photos Invités directement
        $photosInvite = PhotosInvite::firstOrCreate(
            ['salon_id' => $salon->id],
            [
                'mini_title' => 'Photos',
                'title' => 'Photos Invités',
                'first_subtitle' => 'Première section',
                'first_content' => '<p>Contenu de la première section...</p>',
                'second_subtitle' => 'Deuxième section',
                'second_content' => '<p>Contenu de la deuxième section...</p>',
                'first_title_link' => 'Premier lien',
                'first_link' => '#',
                'second_title_link' => 'Deuxième lien',
                'second_link' => '#',
                'third_subtitle' => 'Troisième section',
                'salon_id' => $salon->id,
            ]
        );

        $photosInvite->update($data);

        Notification::make()
            ->success()
            ->title('Page Photos Invités sauvegardée')
            ->body('Le contenu de la page Photos Invités a été mis à jour avec succès.')
            ->send();
    }

    public function getTitle(): string
    {
        return 'Page Photos Invités';
    }

    // Afficher seulement si show_photos_invites est activé pour le salon
    public static function shouldRegisterNavigation(): bool
    {
        try {
            $salon = Filament::getTenant();
            return $salon && $salon->show_photos_invites;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
