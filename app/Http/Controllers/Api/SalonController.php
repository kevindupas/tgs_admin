<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SalonController extends Controller
{
    /**
     * Liste tous les salons
     */
    public function index(Request $request): JsonResponse
    {
        $query = Salon::query();

        // Filtres optionnels
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('upcoming') && $request->upcoming) {
            $query->where('event_date', '>=', now());
        }

        if ($request->has('past') && $request->past) {
            $query->where('event_date', '<', now());
        }

        if ($request->has('e2c') && $request->e2c) {
            $query->where('e2c', true);
        }

        $salons = $query->orderBy('event_date', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $salons
        ]);
    }

    /**
     * Affiche un salon spécifique avec toutes ses données
     */
    public function show(Salon $salon): JsonResponse
    {
        $salon->load([
            'categories',
            'availabilities',
            'partners',
            'ticketPrices',
            'e2cContent'
        ]);

        return response()->json([
            'success' => true,
            'data' => $salon
        ]);
    }

    /**
     * Statistiques d'un salon
     */
    public function stats(Salon $salon): JsonResponse
    {
        $stats = [
            'salon_info' => [
                'name' => $salon->name,
                'event_date' => $salon->event_date,
                'edition' => $salon->edition,
                'halls' => $salon->halls,
                'scenes' => $salon->scenes,
                'invites' => $salon->invites,
                'exposants' => $salon->exposants,
                'associations' => $salon->associations,
                'animations' => $salon->animations,
            ],
            'content_stats' => [
                'total_articles' => $salon->articles()->count(),
                'published_articles' => $salon->articles()->wherePivot('is_published', true)->count(),
                'featured_articles' => $salon->articles()->wherePivot('is_featured', true)->count(),
                'scheduled_articles' => $salon->articles()->wherePivot('is_scheduled', true)->count(),
                'total_categories' => $salon->categories()->count(),
                'total_partners' => $salon->partners()->count(),
                'total_faqs' => $salon->faqs()->count(),
                'total_practical_infos' => $salon->practicalInfos()->count(),
            ],
            'e2c_stats' => [
                'e2c_enabled' => $salon->e2c,
                'e2c_articles_count' => $salon->e2c ? $salon->e2cArticles()->count() : 0,
                'e2c_jury_count' => $salon->e2c ? $salon->e2cJury()->count() : 0,
                'e2c_participants_count' => $salon->e2c ? $salon->e2cParticipants()->count() : 0,
            ],
            'pages_enabled' => [
                'show_presses' => $salon->show_presses,
                'show_photos_invites' => $salon->show_photos_invites,
                'show_become_an_exhibitor' => $salon->show_become_an_exhibitor,
                'show_become_a_staff' => $salon->show_become_a_staff,
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
