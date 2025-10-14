<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Partners",
 *     description="Gestion des partenaires par salon"
 * )
 */
class PartnerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/partners",
     *     summary="Liste des partenaires",
     *     description="Récupère tous les partenaires d'un salon",
     *     operationId="getSalonPartners",
     *     tags={"Partners"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Partenaires récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Nintendo"),
     *                     @OA\Property(property="logo", type="string"),
     *                     @OA\Property(property="url", type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Salon $salon): JsonResponse
    {
        $partners = $salon->partners()
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $partners
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/partners/{partner}",
     *     summary="Détail d'un partenaire",
     *     description="Récupère les informations d'un partenaire spécifique",
     *     operationId="getSalonPartner",
     *     tags={"Partners"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="partner",
     *         in="path",
     *         description="ID du partenaire",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Partenaire récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="logo", type="string"),
     *                 @OA\Property(property="url", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Partenaire non trouvé"
     *     )
     * )
     */
    public function show(Salon $salon, Partner $partner): JsonResponse
    {
        if ($partner->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Partenaire non trouvé dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $partner
        ]);
    }
}
