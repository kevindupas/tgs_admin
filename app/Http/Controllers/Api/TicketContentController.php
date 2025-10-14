<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketContent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Ticket Contents",
 *     description="Gestion des contenus de billets globaux"
 * )
 */
class TicketContentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/ticket-contents",
     *     summary="Liste de tous les contenus de billets",
     *     description="Récupère tous les contenus de billets avec pagination et recherche optionnelle",
     *     operationId="getAllTicketContents",
     *     tags={"Ticket Contents"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche dans le nom du contenu",
     *         required=false,
     *         @OA\Schema(type="string", example="accès")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", example=50)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contenus de billets récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * Liste tous les contenus de billets
     */
    public function index(Request $request): JsonResponse
    {
        $query = TicketContent::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $ticketContents = $query->orderBy('name')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $ticketContents
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/ticket-contents/{ticketContent}",
     *     summary="Détail d'un contenu de billet",
     *     description="Récupère les informations d'un contenu de billet spécifique",
     *     operationId="getTicketContent",
     *     tags={"Ticket Contents"},
     *     @OA\Parameter(
     *         name="ticketContent",
     *         in="path",
     *         description="ID du contenu de billet",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contenu de billet récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             )
     *         )
     *     )
     * )
     *
     * Affiche un contenu de billet spécifique
     */
    public function show(TicketContent $ticketContent): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $ticketContent
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/ticket-contents/search",
     *     summary="Recherche de contenus de billets",
     *     description="Recherche de contenus de billets pour autocomplete (limite de 20 résultats)",
     *     operationId="searchTicketContents",
     *     tags={"Ticket Contents"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Terme de recherche",
     *         required=false,
     *         @OA\Schema(type="string", example="vip")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Résultats de recherche",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * Recherche de contenus de billets (pour autocomplete)
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $ticketContents = TicketContent::where('name', 'like', '%' . $query . '%')
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $ticketContents
        ]);
    }
}
