<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="FAQs",
 *     description="Gestion des FAQs par salon"
 * )
 */
class FaqController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/faqs",
     *     summary="Liste des FAQs",
     *     description="Récupère toutes les questions fréquentes d'un salon",
     *     operationId="getSalonFaqs",
     *     tags={"FAQs"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FAQs récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Comment venir au salon ?"),
     *                     @OA\Property(property="content", type="string"),
     *                     @OA\Property(property="order", type="integer")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Salon $salon): JsonResponse
    {
        $faqs = $salon->faqs()
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $faqs
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/faqs/{faq}",
     *     summary="Détail d'une FAQ",
     *     description="Récupère les informations d'une question fréquente spécifique",
     *     operationId="getSalonFaq",
     *     tags={"FAQs"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="faq",
     *         in="path",
     *         description="ID de la FAQ",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FAQ récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="order", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="FAQ non trouvée"
     *     )
     * )
     */
    public function show(Salon $salon, Faq $faq): JsonResponse
    {
        if ($faq->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ non trouvée dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $faq
        ]);
    }
}
