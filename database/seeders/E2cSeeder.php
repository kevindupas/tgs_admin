<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salon;
use App\Models\E2cContent;
use App\Models\E2cArticle;

class E2cSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le premier salon ou en créer un pour le test
        $salon = Salon::first();

        if (!$salon) {
            $this->command->error('Aucun salon trouvé. Veuillez créer un salon d\'abord.');
            return;
        }

        // Activer E2C pour ce salon
        $salon->update(['e2c' => true]);

        // Créer le contenu E2C
        E2cContent::updateOrCreate(
            ['salon_id' => $salon->id],
            [
                'logo' => 'https://via.placeholder.com/300x200?text=E2C+Logo',
                'title' => 'Escapade à Cosplay - Edition ' . $salon->edition,
                'text' => '<p>Bienvenue au concours E2C ! Découvrez les meilleurs cosplayers de la région et votez pour vos préférés.</p><p>Le jury sera composé de professionnels reconnus dans le milieu du cosplay et de la création de costumes.</p>',
            ]
        );

        // Créer des membres du jury
        $juryMembers = [
            [
                'title' => 'Sophie Martin',
                'content' => '<p>Cosplayeuse professionnelle depuis 10 ans, spécialisée dans les armures et les créations fantasy.</p><p>Gagnante de plusieurs concours internationaux.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Sophie+Martin',
                'social_links' => [
                    'instagram' => 'https://instagram.com/sophiemart_cosplay',
                    'twitter' => 'https://twitter.com/sophiemart_cos',
                ],
            ],
            [
                'title' => 'Thomas Dubois',
                'content' => '<p>Créateur de costumes et accessoires pour le cinéma et les conventions.</p><p>Expert en fabrication d\'armes et d\'armures en mousse EVA.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Thomas+Dubois',
                'social_links' => [
                    'instagram' => 'https://instagram.com/thomas_props',
                    'facebook' => 'https://facebook.com/thomasdubois.props',
                ],
            ],
            [
                'title' => 'Emma Chen',
                'content' => '<p>Photographe professionnelle spécialisée dans les portraits de cosplay.</p><p>Plus de 15 ans d\'expérience dans la couverture d\'événements geek.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Emma+Chen',
                'social_links' => [
                    'instagram' => 'https://instagram.com/emma_cosplay_photo',
                    'website' => 'https://emmachen-photography.com',
                ],
            ],
        ];

        foreach ($juryMembers as $index => $member) {
            E2cArticle::create([
                'salon_id' => $salon->id,
                'title' => $member['title'],
                'content' => $member['content'],
                'featured_image' => $member['featured_image'],
                'social_links' => $member['social_links'],
                'is_jury' => true,
                'display_order' => $index + 1,
            ]);
        }

        // Créer des participants
        $participants = [
            [
                'title' => 'Lucie Phantom - Zelda',
                'content' => '<p>Cosplay de Zelda (Breath of the Wild)</p><p>Costume entièrement fait main avec attention aux détails.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Zelda+Cosplay',
                'gallery' => [
                    'https://via.placeholder.com/800x600?text=Detail+1',
                    'https://via.placeholder.com/800x600?text=Detail+2',
                ],
                'social_links' => [
                    'instagram' => 'https://instagram.com/lucie_phantom',
                ],
            ],
            [
                'title' => 'MaxCraft - Iron Man Mark 85',
                'content' => '<p>Armure Iron Man Mark 85 avec LEDs intégrées</p><p>Fabrication en mousse EVA et impression 3D, plus de 200h de travail.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Iron+Man',
                'gallery' => [
                    'https://via.placeholder.com/800x600?text=Armor+Detail+1',
                    'https://via.placeholder.com/800x600?text=Armor+Detail+2',
                    'https://via.placeholder.com/800x600?text=LED+System',
                ],
                'videos' => [
                    'https://www.youtube.com/watch?v=example1',
                ],
                'social_links' => [
                    'instagram' => 'https://instagram.com/maxcraft_cosplay',
                    'youtube' => 'https://youtube.com/@maxcraft',
                ],
            ],
            [
                'title' => 'Sakura Dreams - Sailor Moon',
                'content' => '<p>Cosplay de Sailor Moon version manga original</p><p>Costume cousu main avec tissus importés du Japon.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Sailor+Moon',
                'social_links' => [
                    'instagram' => 'https://instagram.com/sakura_dreams_cos',
                    'tiktok' => 'https://tiktok.com/@sakuradreams',
                ],
            ],
            [
                'title' => 'Dark Knight Props - Batman',
                'content' => '<p>Batman version Dark Knight avec véhicule custom</p><p>Réplique exacte du costume du film avec accessoires fonctionnels.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Batman',
                'gallery' => [
                    'https://via.placeholder.com/800x600?text=Batmobile',
                    'https://via.placeholder.com/800x600?text=Gadgets',
                ],
                'social_links' => [
                    'instagram' => 'https://instagram.com/darkknight_props',
                    'facebook' => 'https://facebook.com/darkknight.props',
                ],
            ],
            [
                'title' => 'Elven Workshop - Elfe Sylvain',
                'content' => '<p>Création originale inspirée de l\'univers fantasy</p><p>Costume avec prothèses en latex et maquillage artistique.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Wood+Elf',
                'gallery' => [
                    'https://via.placeholder.com/800x600?text=Makeup+Detail',
                    'https://via.placeholder.com/800x600?text=Props',
                ],
                'social_links' => [
                    'instagram' => 'https://instagram.com/elven_workshop',
                ],
            ],
        ];

        foreach ($participants as $index => $participant) {
            E2cArticle::create([
                'salon_id' => $salon->id,
                'title' => $participant['title'],
                'content' => $participant['content'],
                'featured_image' => $participant['featured_image'],
                'gallery' => $participant['gallery'] ?? null,
                'videos' => $participant['videos'] ?? null,
                'social_links' => $participant['social_links'],
                'is_jury' => false,
                'display_order' => $index + 1,
            ]);
        }

        $this->command->info('✅ Données E2C créées avec succès !');
        $this->command->info('📊 Salon: ' . $salon->name);
        $this->command->info('👥 Jury: ' . count($juryMembers) . ' membres');
        $this->command->info('🎭 Participants: ' . count($participants) . ' cosplayers');
    }
}
