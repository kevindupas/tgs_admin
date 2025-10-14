<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Availabilities",
 *     description="Gestion des disponibilités par salon"
 * )
 */
class AvailabilityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/availabilities",
     *     summary="Liste des disponibilités",
     *     description="Récupère toutes les disponibilités d'un salon",
     *     operationId="getSalonAvailabilities",
     *     tags={"Availabilities"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Disponibilités récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Vendredi"),
     *                     @OA\Property(property="salon_id", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * Liste toutes les disponibilités d'un salon
     */
    public function index(Salon $salon): JsonResponse
    {
        $availabilities = $salon->availabilities()
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availabilities
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/availabilities/{availability}",
     *     summary="Détail d'une disponibilité",
     *     description="Récupère les informations d'une disponibilité spécifique",
     *     operationId="getSalonAvailability",
     *     tags={"Availabilities"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="availability",
     *         in="path",
     *         description="ID de la disponibilité",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Disponibilité récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="salon_id", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Disponibilité non trouvée"
     *     )
     * )
     */
    public function show(Salon $salon, Availability $availability): JsonResponse
    {
        if ($availability->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Disponibilité non trouvée dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/availabilities/{availability}/articles",
     *     summary="Articles d'une disponibilité",
     *     description="Récupère tous les articles avec une disponibilité spécifique",
     *     operationId="getAvailabilityArticles",
     *     tags={"Availabilities"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="availability",
     *         in="path",
     *         description="ID de la disponibilité",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles de la disponibilité récupérés",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Disponibilité non trouvée"
     *     )
     * )
     */
    public function articles(Salon $salon, Availability $availability): JsonResponse
    {
        if ($availability->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Disponibilité non trouvée dans ce salon'
            ], 404);
        }

        $articles = $salon->articles()
            ->wherePivot('availability_id', $availability->id)
            ->wherePivot('is_published', true)
            ->orderByPivot('display_order', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }
}
