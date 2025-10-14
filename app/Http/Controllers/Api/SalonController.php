<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Salons",
 *     description="Gestion des salons"
 * )
 */
class SalonController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/salons",
     *     summary="Liste de tous les salons",
     *     description="Récupère la liste de tous les salons avec pagination et filtres optionnels",
     *     operationId="getSalons",
     *     tags={"Salons"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche dans le nom du salon",
     *         required=false,
     *         @OA\Schema(type="string", example="Toulouse")
     *     ),
     *     @OA\Parameter(
     *         name="upcoming",
     *         in="query",
     *         description="Filtrer les salons à venir",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="past",
     *         in="query",
     *         description="Filtrer les salons passés",
     *         required=false,
     *         @OA\Schema(type="boolean", example=false)
     *     ),
     *     @OA\Parameter(
     *         name="e2c",
     *         in="query",
     *         description="Filtrer les salons avec E2C activé",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des salons récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Toulouse Game Show 2025"),
     *                         @OA\Property(property="edition", type="string", example="2025"),
     *                         @OA\Property(property="event_date", type="string", example="2025-11-15"),
     *                         @OA\Property(property="e2c", type="boolean", example=true)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
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
     * @OA\Get(
     *     path="/api/v1/salons/{salon}",
     *     summary="Détail d'un salon",
     *     description="Récupère les informations complètes d'un salon spécifique",
     *     operationId="getSalon",
     *     tags={"Salons"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du salon récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Toulouse Game Show 2025"),
     *                 @OA\Property(property="edition", type="string", example="2025"),
     *                 @OA\Property(property="event_date", type="string", example="2025-11-15"),
     *                 @OA\Property(property="ticket_link", type="string"),
     *                 @OA\Property(property="e2c", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Salon non trouvé"
     *     )
     * )
     *
     * Affiche un salon spécifique avec toutes ses données
     */
    public function show(Salon $salon): JsonResponse
    {
        $salon->load([
            'categories',
            'availabilities',
            'faqs',
            'practicalInfos',
            'partners',
            'ticketPrices'
        ]);

        return response()->json([
            'success' => true,
            'data' => $salon
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/stats",
     *     summary="Statistiques d'un salon",
     *     description="Récupère les statistiques (nombre d'articles, catégories, etc.) d'un salon",
     *     operationId="getSalonStats",
     *     tags={"Salons"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_articles", type="integer", example=10),
     *                 @OA\Property(property="total_categories", type="integer", example=5),
     *                 @OA\Property(property="total_partners", type="integer", example=8),
     *                 @OA\Property(property="total_faqs", type="integer", example=12)
     *             )
     *         )
     *     )
     * )
     *
     * Statistiques d'un salon
     */
    public function stats(Salon $salon): JsonResponse
    {
        $stats = [
            'total_articles' => $salon->articles()->count(),
            'total_categories' => $salon->categories()->count(),
            'total_partners' => $salon->partners()->count(),
            'total_faqs' => $salon->faqs()->count(),
            'total_practical_infos' => $salon->practicalInfos()->count(),
            'total_ticket_prices' => $salon->ticketPrices()->count(),
        ];

        if ($salon->e2c) {
            $stats['e2c_jury_count'] = $salon->e2cJury()->count();
            $stats['e2c_participants_count'] = $salon->e2cParticipants()->count();
        }

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
