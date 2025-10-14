<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\E2cArticle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="E2C",
 *     description="Endpoints pour Escapade à Cosplay"
 * )
 */
class E2cController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/api/v1/salons/{salon}/e2c",
     *     summary="Récupérer toutes les données E2C (content, jury, participants)",
     *     description="Retourne en un seul appel le contenu E2C, la liste des membres du jury et la liste des participants",
     *     operationId="getE2CIndex",
     *     tags={"E2C"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche dans les titres des articles E2C",
     *         required=false,
     *         @OA\Schema(type="string", example="zelda")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Données E2C récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="content",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="logo", type="string", example="https://..."),
     *                     @OA\Property(property="title", type="string", example="Escapade à Cosplay 2025"),
     *                     @OA\Property(property="text", type="string", example="<h2>Concours</h2>"),
     *                     @OA\Property(property="salon_id", type="integer", example=1)
     *                 ),
     *                 @OA\Property(
     *                     property="jury",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Marie - Jury"),
     *                         @OA\Property(property="slug", type="string", example="marie-jury"),
     *                         @OA\Property(property="content", type="string"),
     *                         @OA\Property(property="featured_image", type="string"),
     *                         @OA\Property(property="gallery", type="array", @OA\Items(type="string")),
     *                         @OA\Property(property="videos", type="array", @OA\Items(type="string")),
     *                         @OA\Property(property="social_links", type="object"),
     *                         @OA\Property(property="is_jury", type="boolean", example=true),
     *                         @OA\Property(property="display_order", type="integer")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="participants",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="title", type="string"),
     *                         @OA\Property(property="is_jury", type="boolean", example=false)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="E2C non activé pour ce salon",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="E2C non activé pour ce salon")
     *         )
     *     )
     * )
     *
     * Toutes les données E2C d'un salon (content, jury et participants)
     */
    public function index(Request $request, Salon $salon): JsonResponse
    {
        if (!$salon->e2c) {
            return response()->json([
                'success' => false,
                'message' => 'E2C non activé pour ce salon'
            ], 404);
        }

        $query = $salon->e2cArticles();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'content' => $salon->e2cContent,
                'jury' => $salon->e2cJury()->ordered()->get(),
                'participants' => $salon->e2cParticipants()->ordered()->get(),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/api/v1/salons/{salon}/e2c/{e2cArticle}",
     *     summary="Récupérer le détail d'un article E2C",
     *     description="Retourne les informations complètes d'un article E2C spécifique (jury ou participant)",
     *     operationId="getE2CArticle",
     *     tags={"E2C"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="e2cArticle",
     *         in="path",
     *         description="ID de l'article E2C",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article E2C récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Marie - Jury"),
     *                 @OA\Property(property="slug", type="string", example="marie-jury"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="featured_image", type="string"),
     *                 @OA\Property(property="gallery", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="videos", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="social_links", type="object"),
     *                 @OA\Property(property="is_jury", type="boolean"),
     *                 @OA\Property(property="display_order", type="integer"),
     *                 @OA\Property(property="salon_id", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article E2C non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     *
     * Détail d'un article E2C spécifique
     */
    public function show(Salon $salon, E2cArticle $e2cArticle): JsonResponse
    {
        if (!$salon->e2c) {
            return response()->json([
                'success' => false,
                'message' => 'E2C non activé pour ce salon'
            ], 404);
        }

        if ($e2cArticle->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Article E2C non trouvé dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $e2cArticle
        ]);
    }
}
