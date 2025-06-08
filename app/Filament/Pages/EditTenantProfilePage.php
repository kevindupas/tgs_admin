<?php

namespace App\Filament\Pages;

use App\Models\Salon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\TiptapEditor;
use RalphJSmit\Filament\MediaLibrary\Facades\MediaLibrary;
use RalphJSmit\Filament\MediaLibrary\Forms\Components\MediaPicker;
use RalphJSmit\Filament\MediaLibrary\Tables\Columns\MediaColumn;

class EditTenantProfilePage extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Modifier votre salon';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Paramètres du salon')
                    ->tabs([
                        Tabs\Tab::make('Informations générales')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nom du salon')
                                    ->required(),
                                MediaPicker::make('logo')
                                    ->label('Logo')
                                    ->extraAttributes(['class' => 'max-w-md']),
                                MediaPicker::make('event_logo')
                                    ->label('Logo de l\'événement')
                                    ->extraAttributes(['class' => 'max-w-xs']),
                                TextInput::make('event_date')
                                    ->label('Date de l\'événement'),
                                TextInput::make('edition')
                                    ->label('Édition'),
                                ColorPicker::make('edition_color')
                                    ->label('Couleur de l\'édition'),
                                DateTimePicker::make('countdown')
                                    ->label('Compte à rebours')
                                    ->timezone('Europe/Paris'),
                                TextInput::make('website_link')
                                    ->label('Lien du site web')
                                    ->url(),
                            ]),

                        Tabs\Tab::make('Billetterie')
                            ->schema([
                                TextInput::make('ticket_link')
                                    ->label('Lien de billetterie principal')
                                    ->url(),
                                TextInput::make('second_ticket_link')
                                    ->label('Lien de billetterie secondaire')
                                    ->url(),
                                TiptapEditor::make('message_ticket_link')->label('Message de billetterie'),
                            ]),

                        Tabs\Tab::make('Parc et lieu')
                            ->schema([
                                TextInput::make('park_address')
                                    ->label('Adresse du parc'),
                                TextInput::make('park_link')
                                    ->label('Lien Google Maps')
                                    ->url(),
                            ]),

                        Tabs\Tab::make('Footer')
                            ->schema([
                                MediaPicker::make('footer_image')
                                    ->label('Image du footer'),

                                MediaPicker::make('all_salon_image')
                                    ->label('Image tous les salons'),

                                MediaPicker::make('salon_pop_culture_image')
                                    ->label('Image salon pop culture'),

                                MediaPicker::make('youtube_image')
                                    ->label('Image YouTube')
                            ]),

                        Tabs\Tab::make('Découverte du salon')
                            ->schema([
                                TextInput::make('title_discover')
                                    ->label('Titre de la section découverte'),

                                TiptapEditor::make('content_discover'),

                                TextInput::make('youtube_discover')
                                    ->label('Lien YouTube de découverte')
                                    ->url(),
                            ]),

                        Tabs\Tab::make('Chiffres clés')
                            ->schema([
                                TextInput::make('halls')
                                    ->label('Nombre de halls'),
                                TextInput::make('scenes')
                                    ->label('Nombre de scènes'),
                                TextInput::make('invites')
                                    ->label('Nombre d\'invités'),
                                TextInput::make('exposants')
                                    ->label('Nombre d\'exposants'),
                                TextInput::make('associations')
                                    ->label('Nombre d\'associations'),
                                TextInput::make('animations')
                                    ->label('Nombre d\'animations'),
                            ]),

                        Tabs\Tab::make('Documents')
                            ->schema([
                                FileUpload::make('plan_pdf')
                                    ->label('Plan (PDF)')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->directory('plans'),
                                FileUpload::make('planning_pdf')
                                    ->label('Planning (PDF)')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->directory('plannings'),
                                FileUpload::make('presse_kit')
                                    ->label('Kit presse')
                                    ->acceptedFileTypes(['application/pdf', 'application/zip'])
                                    ->directory('presse-kits'),
                            ]),

                        Tabs\Tab::make('Réseaux sociaux')
                            ->schema([
                                TextInput::make('facebook_link')
                                    ->label('Lien Facebook')
                                    ->url(),
                                TextInput::make('twitter_link')
                                    ->label('Lien Twitter/X')
                                    ->url(),
                                TextInput::make('instagram_link')
                                    ->label('Lien Instagram')
                                    ->url(),
                                TextInput::make('youtube_link')
                                    ->label('Lien YouTube')
                                    ->url(),
                                TextInput::make('tiktok_link')
                                    ->label('Lien TikTok')
                                    ->url(),
                            ]),

                        Tabs\Tab::make('À propos')
                            ->schema([
                                TiptapEditor::make('about_us')->label('À propos de nous'),
                                TiptapEditor::make('practical_info')->label('Informations pratiques'),
                            ]),

                        Tabs\Tab::make('E2C')
                            ->schema([
                                Toggle::make('e2c')
                                    ->label('E2C')
                                    ->helperText('Si vous souhaitez afficher le E2C, veuillez activer cette option')
                                    ->default(false),
                            ]),
                    ])
                    ->columnSpan('full'),
            ]);
    }
}
