<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salon;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\Availability;
use App\Models\Faq;
use App\Models\PracticalInfo;
use App\Models\Partner;
use App\Models\TicketPrice;
use App\Models\E2cContent;
use App\Models\E2cArticle;
use App\Models\Presse;
use App\Models\PhotosInvite;
use App\Models\BecomeAnExhibitor;
use App\Models\BecomeAStaff;
use App\Models\Tag;
use App\Models\TicketContent;
use Illuminate\Support\Str;

class CompleteApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Démarrage du seeding complet...');

        // 1. Créer un salon
        $salon = $this->createSalon();

        // 2. Créer des catégories
        $categories = $this->createCategories($salon);

        // 3. Créer des disponibilités
        $availabilities = $this->createAvailabilities($salon);

        // 4. Créer des tags
        $tags = $this->createTags();

        // 5. Créer des articles
        $articles = $this->createArticles($salon, $categories, $availabilities, $tags);

        // 6. Créer des FAQs
        $this->createFaqs($salon);

        // 7. Créer des infos pratiques
        $this->createPracticalInfos($salon);

        // 8. Créer des partenaires
        $this->createPartners($salon);

        // 9. Créer des prix de billets
        $this->createTicketPrices($salon);

        // 10. Créer le contenu E2C
        $this->createE2cContent($salon);

        // 11. Créer les pages spéciales
        $this->createSpecialPages($salon);

        // 12. Créer des ticket contents
        $this->createTicketContents();

        $this->command->info('✅ Seeding terminé avec succès !');
    }

    private function createSalon(): Salon
    {
        $salon = Salon::create([
            'name' => 'Toulouse Game Show 2025',
            'edition' => '2025',
            'edition_color' => '#FF6B35',
            'event_date' => '2025-11-15',
            'countdown' => '2025-11-15 10:00:00',
            'ticket_link' => 'https://billetterie.tgs.com',
            'message_ticket_link' => 'Réservez dès maintenant !',
            'website_link' => 'https://toulousegameshow.fr',
            'park_address' => 'Parc des Expositions, Toulouse',
            'park_link' => 'https://maps.google.com',
            'halls' => '3 halls',
            'scenes' => '2 scènes',
            'invites' => '50+ invités',
            'exposants' => '200+ exposants',
            'associations' => '30+ associations',
            'animations' => '100+ animations',
            'facebook_link' => 'https://facebook.com/toulousegameshow',
            'twitter_link' => 'https://twitter.com/toulousegameshow',
            'instagram_link' => 'https://instagram.com/toulousegameshow',
            'youtube_link' => 'https://youtube.com/@toulousegameshow',
            'tiktok_link' => 'https://tiktok.com/@toulousegameshow',
            'about_us' => '<h2>À propos de nous</h2><p>Le Toulouse Game Show est le plus grand événement gaming du Sud-Ouest !</p>',
            'practical_info' => '<h2>Infos pratiques</h2><p>Ouvert de 10h à 19h tous les jours.</p>',
            'title_discover' => 'Découvrez le TGS 2025',
            'content_discover' => '<p>Une expérience gaming unique avec des compétitions eSport, des tournois, des showcases et bien plus !</p>',
            'youtube_discover' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'show_presses' => true,
            'show_photos_invites' => true,
            'show_become_an_exhibitor' => true,
            'show_become_a_staff' => true,
            'e2c' => true,
        ]);

        $this->command->info("✅ Salon créé: {$salon->name}");
        return $salon;
    }

    private function createCategories(Salon $salon): array
    {
        $categoriesData = [
            ['name' => 'Invités'],
            ['name' => 'Exposants'],
            ['name' => 'Animations'],
            ['name' => 'Tournois'],
            ['name' => 'Conférences'],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[] = Categorie::create(array_merge($data, ['salon_id' => $salon->id]));
        }

        $this->command->info("✅ " . count($categories) . " catégories créées");
        return $categories;
    }

    private function createAvailabilities(Salon $salon): array
    {
        $availabilitiesData = [
            ['name' => 'Vendredi'],
            ['name' => 'Samedi'],
            ['name' => 'Dimanche'],
        ];

        $availabilities = [];
        foreach ($availabilitiesData as $data) {
            $availabilities[] = Availability::create(array_merge($data, ['salon_id' => $salon->id]));
        }

        $this->command->info("✅ " . count($availabilities) . " disponibilités créées");
        return $availabilities;
    }

    private function createTags(): array
    {
        $tagsData = [
            ['name' => 'Gaming', 'slug' => 'gaming'],
            ['name' => 'eSport', 'slug' => 'esport'],
            ['name' => 'Cosplay', 'slug' => 'cosplay'],
            ['name' => 'Retrogaming', 'slug' => 'retrogaming'],
            ['name' => 'VR', 'slug' => 'vr'],
        ];

        $tags = [];
        foreach ($tagsData as $data) {
            $tags[] = Tag::create($data);
        }

        $this->command->info("✅ " . count($tags) . " tags créés");
        return $tags;
    }

    private function createArticles(Salon $salon, array $categories, array $availabilities, array $tags): array
    {
        $articlesData = [
            [
                'title' => 'Kayane - Championne de Fighting Games',
                'subtitle' => 'La star internationale du fighting game',
                'content' => '<h2>Kayane</h2><p>Rencontrez Kayane, championne mondiale de jeux de combat et streameuse de renom.</p><p>Sessions de dédicaces et démonstrations prévues tout le week-end !</p>',
                'category_index' => 0,
                'is_featured' => true,
            ],
            [
                'title' => 'ZeratoR - Streameur & Organisateur',
                'subtitle' => 'Le créateur de la Z Event',
                'content' => '<h2>ZeratoR</h2><p>ZeratoR sera présent pour parler de ses projets et rencontrer sa communauté.</p>',
                'category_index' => 0,
                'is_featured' => true,
            ],
            [
                'title' => 'Stand Nintendo',
                'subtitle' => 'Testez les dernières nouveautés',
                'content' => '<h2>Nintendo</h2><p>Découvrez en avant-première les prochaines sorties Nintendo Switch !</p>',
                'category_index' => 1,
                'is_featured' => false,
            ],
            [
                'title' => 'Tournoi League of Legends',
                'subtitle' => 'Compétition amateur - 5000€ de cashprize',
                'content' => '<h2>Tournoi LoL</h2><p>Inscrivez votre équipe pour le tournoi amateur avec 5000€ à gagner !</p>',
                'category_index' => 3,
                'is_featured' => true,
                'is_scheduled' => true,
                'start_datetime' => '2025-11-15 14:00:00',
                'end_datetime' => '2025-11-15 18:00:00',
            ],
            [
                'title' => 'Conférence : L\'avenir du jeu vidéo',
                'subtitle' => 'Débat avec des professionnels du secteur',
                'content' => '<h2>L\'avenir du jeu vidéo</h2><p>Une table ronde avec des développeurs et éditeurs français.</p>',
                'category_index' => 4,
                'is_featured' => false,
                'is_scheduled' => true,
                'start_datetime' => '2025-11-16 15:00:00',
                'end_datetime' => '2025-11-16 16:30:00',
            ],
        ];

        $articles = [];
        foreach ($articlesData as $index => $data) {
            $article = Article::create([
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'slug' => Str::slug($data['title']),
                'content' => $data['content'],
                'featured_image' => 'https://via.placeholder.com/1200x800?text=' . urlencode($data['title']),
                'is_published' => true,
                'published_at' => now(),
            ]);

            // Attacher à un salon avec pivot
            $article->salons()->attach($salon->id, [
                'category_id' => $categories[$data['category_index']]->id,
                'availability_id' => $availabilities[array_rand($availabilities)]->id,
                'is_featured' => $data['is_featured'],
                'is_published' => true,
                'published_at' => now(),
                'content_salon' => $data['content'],
                'is_scheduled' => $data['is_scheduled'] ?? false,
                'start_datetime' => $data['start_datetime'] ?? null,
                'end_datetime' => $data['end_datetime'] ?? null,
                'display_order' => $index + 1,
            ]);

            // Attacher des tags
            $article->tags()->attach($tags[array_rand($tags)]->id);

            $articles[] = $article;
        }

        $this->command->info("✅ " . count($articles) . " articles créés");
        return $articles;
    }

    private function createFaqs(Salon $salon): void
    {
        $faqsData = [
            [
                'question' => 'Quels sont les horaires du salon ?',
                'answer' => 'Le salon est ouvert de 10h à 19h tous les jours.',
                'order' => 1,
            ],
            [
                'question' => 'Où acheter mes billets ?',
                'answer' => 'Les billets sont disponibles sur notre site officiel et sur place (selon disponibilités).',
                'order' => 2,
            ],
            [
                'question' => 'Y a-t-il un parking ?',
                'answer' => 'Oui, un parking gratuit est disponible au Parc des Expositions.',
                'order' => 3,
            ],
            [
                'question' => 'Le cosplay est-il autorisé ?',
                'answer' => 'Oui ! Le cosplay est même encouragé. Un concours E2C est organisé.',
                'order' => 4,
            ],
        ];

        foreach ($faqsData as $data) {
            Faq::create(array_merge($data, ['salon_id' => $salon->id]));
        }

        $this->command->info("✅ " . count($faqsData) . " FAQs créées");
    }

    private function createPracticalInfos(Salon $salon): void
    {
        $infosData = [
            [
                'name' => 'Accès & Transport',
                'content' => '<p>Métro ligne B - arrêt "Parc des Expos"</p><p>Bus 23, 45, 67</p>',
                'mini_content' => 'Transports en commun disponibles',
                'icon' => '🚇',
                'order' => 1,
            ],
            [
                'name' => 'Restauration',
                'content' => '<p>Food trucks et stands de restauration sur place.</p>',
                'mini_content' => 'Restauration sur place',
                'icon' => '🍔',
                'order' => 2,
            ],
            [
                'name' => 'Accessibilité PMR',
                'content' => '<p>Le salon est entièrement accessible aux personnes à mobilité réduite.</p>',
                'mini_content' => 'Accès PMR',
                'icon' => '♿',
                'order' => 3,
            ],
        ];

        foreach ($infosData as $data) {
            PracticalInfo::create(array_merge($data, ['salon_id' => $salon->id]));
        }

        $this->command->info("✅ " . count($infosData) . " infos pratiques créées");
    }

    private function createPartners(Salon $salon): void
    {
        $partnersData = [
            [
                'name' => 'Nintendo',
                'logo' => 'https://via.placeholder.com/300x150?text=Nintendo',
                'sponsor' => true,
            ],
            [
                'name' => 'PlayStation',
                'logo' => 'https://via.placeholder.com/300x150?text=PlayStation',
                'sponsor' => true,
            ],
            [
                'name' => 'Razer',
                'logo' => 'https://via.placeholder.com/300x150?text=Razer',
                'sponsor' => false,
            ],
        ];

        foreach ($partnersData as $data) {
            Partner::create(array_merge($data, ['salon_id' => $salon->id]));
        }

        $this->command->info("✅ " . count($partnersData) . " partenaires créés");
    }

    private function createTicketPrices(Salon $salon): void
    {
        $pricesData = [
            [
                'name' => 'Pass 1 Jour',
                'price' => 15.00,
                'sold_out' => false,
                'contents' => ['Accès à toutes les zones', 'Entrée pour 1 journée'],
            ],
            [
                'name' => 'Pass 3 Jours',
                'price' => 35.00,
                'sold_out' => false,
                'contents' => ['Accès à toutes les zones', 'Entrée pour 3 jours', 'Badge collector'],
            ],
            [
                'name' => 'Pass VIP',
                'price' => 75.00,
                'sold_out' => false,
                'contents' => ['Accès VIP', 'Coupe-file', 'Goodies exclusifs', 'Meet & Greet'],
            ],
        ];

        foreach ($pricesData as $data) {
            TicketPrice::create(array_merge($data, ['salon_id' => $salon->id]));
        }

        $this->command->info("✅ " . count($pricesData) . " tarifs de billets créés");
    }

    private function createE2cContent(Salon $salon): void
    {
        E2cContent::create([
            'salon_id' => $salon->id,
            'logo' => 'https://via.placeholder.com/300x200?text=E2C+Logo',
            'title' => 'Escapade à Cosplay 2025',
            'text' => '<h2>Concours de Cosplay</h2><p>Participez au plus grand concours de cosplay du Sud-Ouest !</p><p>Jury professionnel et lots à gagner.</p>',
        ]);

        // Jury
        $juryData = [
            [
                'title' => 'Marie Cosplay Pro',
                'content' => '<p>Cosplayeuse professionnelle depuis 10 ans.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Marie',
                'is_jury' => true,
            ],
            [
                'title' => 'Jean Props Master',
                'content' => '<p>Expert en fabrication de props et armures.</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Jean',
                'is_jury' => true,
            ],
        ];

        // Participants
        $participantsData = [
            [
                'title' => 'Participant 1 - Zelda',
                'content' => '<p>Cosplay de Zelda BOTW</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=Zelda',
                'is_jury' => false,
            ],
            [
                'title' => 'Participant 2 - Iron Man',
                'content' => '<p>Armure Iron Man avec LEDs</p>',
                'featured_image' => 'https://via.placeholder.com/600x800?text=IronMan',
                'is_jury' => false,
            ],
        ];

        $allE2cArticles = array_merge($juryData, $participantsData);
        foreach ($allE2cArticles as $index => $data) {
            E2cArticle::create(array_merge($data, [
                'salon_id' => $salon->id,
                'display_order' => $index + 1,
                'social_links' => ['instagram' => 'https://instagram.com/example'],
            ]));
        }

        $this->command->info("✅ Contenu E2C créé avec " . count($allE2cArticles) . " articles");
    }

    private function createSpecialPages(Salon $salon): void
    {
        Presse::create([
            'salon_id' => $salon->id,
            'title' => 'Espace Presse',
            'content' => '<h2>Espace Presse</h2><p>Accréditations et kit presse disponibles.</p>',
            'contact_email' => 'presse@tgs.com',
        ]);

        PhotosInvite::create([
            'salon_id' => $salon->id,
            'title' => 'Photos des Invités',
            'content' => '<h2>Galerie Photos</h2><p>Découvrez les photos de nos invités.</p>',
            'gallery' => [
                'https://via.placeholder.com/800x600?text=Photo1',
                'https://via.placeholder.com/800x600?text=Photo2',
            ],
        ]);

        BecomeAnExhibitor::create([
            'salon_id' => $salon->id,
            'title' => 'Devenir Exposant',
            'content' => '<h2>Exposez au TGS</h2><p>Contactez-nous pour réserver votre stand.</p>',
            'form_link' => 'https://tgs.com/exposant-form',
        ]);

        BecomeAStaff::create([
            'salon_id' => $salon->id,
            'title' => 'Devenir Bénévole',
            'content' => '<h2>Rejoignez l\'équipe</h2><p>Nous recherchons des bénévoles motivés !</p>',
            'form_link' => 'https://tgs.com/staff-form',
        ]);

        $this->command->info("✅ Pages spéciales créées");
    }

    private function createTicketContents(): void
    {
        $contentsData = [
            [
                'title' => 'Infos Billetterie Générale',
                'content' => '<h2>Billetterie</h2><p>Tous les tarifs et informations.</p>',
                'slug' => 'billetterie-generale',
            ],
            [
                'title' => 'Conditions de Vente',
                'content' => '<h2>CGV</h2><p>Conditions générales de vente des billets.</p>',
                'slug' => 'conditions-vente',
            ],
        ];

        foreach ($contentsData as $data) {
            TicketContent::create($data);
        }

        $this->command->info("✅ " . count($contentsData) . " contenus de billetterie créés");
    }
}
