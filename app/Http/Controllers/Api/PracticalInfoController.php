<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PracticalInfo;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Practical Infos",
 *     description="Gestion des informations pratiques par salon"
 * )
 */
class PracticalInfoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/practical-infos",
     *     summary="Liste des infos pratiques",
     *     description="Récupère toutes les informations pratiques d'un salon",
     *     operationId="getSalonPracticalInfos",
     *     tags={"Practical Infos"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Infos pratiques récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Horaires"),
     *                     @OA\Property(property="mini_content", type="string"),
     *                     @OA\Property(property="content", type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Salon $salon): JsonResponse
    {
        $practicalInfos = $salon->practicalInfos()
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $practicalInfos
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/practical-infos/{practicalInfo}",
     *     summary="Détail d'une info pratique",
     *     description="Récupère les informations d'une info pratique spécifique",
     *     operationId="getSalonPracticalInfo",
     *     tags={"Practical Infos"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="practicalInfo",
     *         in="path",
     *         description="ID de l'info pratique",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Info pratique récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="mini_content", type="string"),
     *                 @OA\Property(property="content", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Info pratique non trouvée"
     *     )
     * )
     */
    public function show(Salon $salon, PracticalInfo $practicalInfo): JsonResponse
    {
        if ($practicalInfo->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Info pratique non trouvée dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $practicalInfo
        ]);
    }
}
