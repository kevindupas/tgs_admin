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

class SimpleApiSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Démarrage du seeding...');

        // 1. Salon
        $salon = Salon::create([
            'name' => 'Toulouse Game Show 2025',
            'edition' => '2025',
            'edition_color' => '#FF6B35',
            'event_date' => '2025-11-15',
            'e2c' => true,
        ]);
        $this->command->info("✅ Salon: {$salon->name}");

        // 2. Catégories
        $categories = [];
        foreach (['Invités', 'Exposants', 'Animations', 'Tournois'] as $name) {
            $categories[] = Categorie::create(['name' => $name, 'salon_id' => $salon->id]);
        }
        $this->command->info("✅ " . count($categories) . " catégories");

        // 3. Disponibilités
        $availabilities = [];
        foreach (['Vendredi', 'Samedi', 'Dimanche'] as $name) {
            $availabilities[] = Availability::create(['name' => $name, 'salon_id' => $salon->id]);
        }
        $this->command->info("✅ " . count($availabilities) . " disponibilités");

        // 4. Tags
        $tags = [];
        foreach (['Gaming', 'eSport', 'Cosplay'] as $name) {
            $tags[] = Tag::create(['name' => $name]);
        }
        $this->command->info("✅ " . count($tags) . " tags");

        // 5. Articles
        $articlesData = [
            ['title' => 'Kayane - Championne Fighting Games', 'cat' => 0],
            ['title' => 'ZeratoR - Streameur', 'cat' => 0],
            ['title' => 'Stand Nintendo', 'cat' => 1],
            ['title' => 'Tournoi League of Legends', 'cat' => 3],
        ];

        foreach ($articlesData as $data) {
            $article = Article::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'content' => '<h2>' . $data['title'] . '</h2><p>Description de l\'article</p>',
                'featured_image' => 'https://via.placeholder.com/1200x800?text=' . urlencode($data['title']),
            ]);

            $article->salons()->attach($salon->id, [
                'category_id' => $categories[$data['cat']]->id,
                'availability_id' => $availabilities[0]->id,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
                'content_salon' => '<p>' . $data['title'] . '</p>',
                'display_order' => 1,
            ]);
        }
        $this->command->info("✅ " . count($articlesData) . " articles");

        // 6. FAQs
        $faqs = [
            ['name' => 'Horaires ?', 'content' => '<p>10h à 19h tous les jours</p>', 'order' => 1],
            ['name' => 'Billets ?', 'content' => '<p>Sur notre site</p>', 'order' => 2],
        ];
        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['salon_id' => $salon->id]));
        }
        $this->command->info("✅ " . count($faqs) . " FAQs");

        // 7. Infos Pratiques
        $infos = [
            ['name' => 'Transport', 'mini_content' => 'Métro ligne B', 'content' => '<p>Métro ligne B - arrêt Parc des Expos</p>', 'order' => 1],
            ['name' => 'Restauration', 'mini_content' => 'Food trucks', 'content' => '<p>Food trucks sur place</p>', 'order' => 2],
        ];
        foreach ($infos as $info) {
            PracticalInfo::create(array_merge($info, ['salon_id' => $salon->id]));
        }
        $this->command->info("✅ " . count($infos) . " infos pratiques");

        // 8. Partenaires
        $partners = [
            ['name' => 'Nintendo', 'logo' => 'https://via.placeholder.com/300x150?text=Nintendo', 'sponsor' => true],
            ['name' => 'PlayStation', 'logo' => 'https://via.placeholder.com/300x150?text=PlayStation', 'sponsor' => true],
        ];
        foreach ($partners as $partner) {
            Partner::create(array_merge($partner, ['salon_id' => $salon->id]));
        }
        $this->command->info("✅ " . count($partners) . " partenaires");

        // 9. Prix Billets
        $prices = [
            ['name' => 'Pass 1 Jour', 'price' => 15.00, 'sold_out' => false, 'contents' => ['Accès 1 jour']],
            ['name' => 'Pass 3 Jours', 'price' => 35.00, 'sold_out' => false, 'contents' => ['Accès 3 jours', 'Badge']],
        ];
        foreach ($prices as $price) {
            TicketPrice::create(array_merge($price, ['salon_id' => $salon->id]));
        }
        $this->command->info("✅ " . count($prices) . " prix de billets");

        // 10. E2C
        E2cContent::create([
            'salon_id' => $salon->id,
            'logo' => 'https://via.placeholder.com/300x200?text=E2C',
            'title' => 'Escapade à Cosplay 2025',
            'text' => '<h2>Concours Cosplay</h2><p>Participez au concours !</p>',
        ]);

        $e2cData = [
            [
                'title' => 'Marie - Jury',
                'content' => '<p>Cosplayeuse professionnelle depuis 10 ans</p>',
                'is_jury' => true,
                'photographer' => 'Jean Dupont Photography',
                'photographer_link' => 'https://instagram.com/jeandupont_photo',
                'social_links' => [
                    'facebook' => 'https://facebook.com/marie.cosplay',
                    'instagram' => 'https://instagram.com/marie_cosplay',
                    'twitter' => 'https://twitter.com/marie_cosplay',
                    'youtube' => 'https://youtube.com/@mariecosplay',
                    'tiktok' => 'https://tiktok.com/@marie_cosplay',
                    'website' => 'https://marie-cosplay.com',
                    'wikipedia' => 'https://wikipedia.org/wiki/Marie_Cosplay',
                    'twitch' => 'https://twitch.tv/marie_cosplay',
                ]
            ],
            [
                'title' => 'Jean - Jury',
                'content' => '<p>Expert en fabrication de props et armures</p>',
                'is_jury' => true,
                'photographer' => 'Studio Photo TGS',
                'photographer_link' => 'https://studiophotostgs.com',
                'social_links' => [
                    'facebook' => 'https://facebook.com/jean.props',
                    'instagram' => 'https://instagram.com/jean_props',
                    'twitter' => 'https://twitter.com/jean_props',
                    'youtube' => 'https://youtube.com/@jeanprops',
                    'tiktok' => 'https://tiktok.com/@jean_props',
                    'website' => 'https://jeanprops-workshop.com',
                    'wikipedia' => 'https://wikipedia.org/wiki/Jean_Props',
                    'twitch' => 'https://twitch.tv/jeanprops',
                ]
            ],
            [
                'title' => 'Participant 1 - Zelda',
                'content' => '<p>Cosplay de la Princesse Zelda - Breath of the Wild</p>',
                'is_jury' => false,
                'photographer' => 'Marie Dubois',
                'photographer_link' => 'https://instagram.com/marie_dubois_photos',
                'social_links' => [
                    'facebook' => 'https://facebook.com/zelda.cosplayer',
                    'instagram' => 'https://instagram.com/zelda_cosplay',
                    'twitter' => 'https://twitter.com/zelda_cosplay',
                    'youtube' => 'https://youtube.com/@zeldacosplay',
                    'tiktok' => 'https://tiktok.com/@zelda_cosplay',
                    'website' => 'https://zelda-cosplay-portfolio.com',
                    'wikipedia' => 'https://wikipedia.org/wiki/Zelda_Cosplay',
                    'twitch' => 'https://twitch.tv/zelda_cosplay',
                ]
            ],
            [
                'title' => 'Participant 2 - Iron Man',
                'content' => '<p>Armure Iron Man Mark 50 entièrement fabriquée à la main</p>',
                'is_jury' => false,
                'photographer' => 'PhotoCon Studio',
                'photographer_link' => 'https://instagram.com/photocon_studio',
                'social_links' => [
                    'facebook' => 'https://facebook.com/ironman.armor',
                    'instagram' => 'https://instagram.com/ironman_armor',
                    'twitter' => 'https://twitter.com/ironman_armor',
                    'youtube' => 'https://youtube.com/@ironmanarmor',
                    'tiktok' => 'https://tiktok.com/@ironman_armor',
                    'website' => 'https://ironman-armor-cosplay.com',
                    'wikipedia' => 'https://wikipedia.org/wiki/Iron_Man_Cosplay',
                    'twitch' => 'https://twitch.tv/ironman_armor',
                ]
            ],
        ];

        foreach ($e2cData as $index => $data) {
            E2cArticle::create(array_merge($data, [
                'salon_id' => $salon->id,
                'featured_image' => 'https://via.placeholder.com/600x800?text=' . urlencode($data['title']),
                'display_order' => $index + 1,
            ]));
        }
        $this->command->info("✅ E2C avec " . count($e2cData) . " articles");

        // 11. Pages Spéciales (simplified)
        BecomeAnExhibitor::create([
            'salon_id' => $salon->id,
            'title' => 'Devenir Exposant',
            'content' => '<p>Réservez votre stand</p>',
        ]);

        BecomeAStaff::create([
            'salon_id' => $salon->id,
            'title' => 'Devenir Bénévole',
            'content' => '<p>Rejoignez l\'équipe</p>',
        ]);
        $this->command->info("✅ Pages spéciales");

        // 12. Ticket Contents
        TicketContent::create(['name' => 'Billetterie Générale']);
        TicketContent::create(['name' => 'Conditions de Vente']);
        $this->command->info("✅ 2 contenus billetterie");

        $this->command->info('🎉 Seeding terminé !');
    }
}
