<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Pages",
 *     description="Pages spéciales par salon"
 * )
 */
class PageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/presse",
     *     summary="Page Presse",
     *     description="Récupère le contenu de la page presse d'un salon",
     *     operationId="getPressePage",
     *     tags={"Pages"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page presse récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page presse non activée"
     *     )
     * )
     *
     * Page Presse
     */
    public function presse(Salon $salon): JsonResponse
    {
        if (!$salon->show_presses) {
            return response()->json([
                'success' => false,
                'message' => 'Page presse non activée pour ce salon'
            ], 404);
        }

        $presse = $salon->presse;

        return response()->json([
            'success' => true,
            'data' => $presse
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/photos-invites",
     *     summary="Page Photos Invités",
     *     description="Récupère le contenu de la page photos invités d'un salon",
     *     operationId="getPhotosInvitesPage",
     *     tags={"Pages"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page photos invités récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page photos invités non activée"
     *     )
     * )
     *
     * Page Photos Invités
     */
    public function photosInvites(Salon $salon): JsonResponse
    {
        if (!$salon->show_photos_invites) {
            return response()->json([
                'success' => false,
                'message' => 'Page photos invités non activée pour ce salon'
            ], 404);
        }

        $photosInvites = $salon->photosInvites;

        return response()->json([
            'success' => true,
            'data' => $photosInvites
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/become-exhibitor",
     *     summary="Page Devenir Exposant",
     *     description="Récupère le contenu de la page devenir exposant d'un salon",
     *     operationId="getBecomeExhibitorPage",
     *     tags={"Pages"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page devenir exposant récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page devenir exposant non activée"
     *     )
     * )
     *
     * Page Devenir Exposant
     */
    public function becomeExhibitor(Salon $salon): JsonResponse
    {
        if (!$salon->show_become_an_exhibitor) {
            return response()->json([
                'success' => false,
                'message' => 'Page devenir exposant non activée pour ce salon'
            ], 404);
        }

        $becomeExhibitor = $salon->becomeAnExhibitor;

        return response()->json([
            'success' => true,
            'data' => $becomeExhibitor
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/become-staff",
     *     summary="Page Devenir Staff",
     *     description="Récupère le contenu de la page devenir staff d'un salon",
     *     operationId="getBecomeStaffPage",
     *     tags={"Pages"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Page devenir staff récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page devenir staff non activée"
     *     )
     * )
     *
     * Page Devenir Staff
     */
    public function becomeStaff(Salon $salon): JsonResponse
    {
        if (!$salon->show_become_a_staff) {
            return response()->json([
                'success' => false,
                'message' => 'Page devenir staff non activée pour ce salon'
            ], 404);
        }

        $becomeStaff = $salon->becomeAStaff;

        return response()->json([
            'success' => true,
            'data' => $becomeStaff
        ]);
    }
}
