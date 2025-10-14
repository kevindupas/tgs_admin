<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="Gestion des tags/filtres globaux"
 * )
 */
class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/tags",
     *     summary="Liste de tous les tags",
     *     description="Récupère tous les tags avec pagination et recherche optionnelle",
     *     operationId="getAllTags",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche dans le nom du tag",
     *         required=false,
     *         @OA\Schema(type="string", example="cosplay")
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
     *         description="Tags récupérés avec succès",
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
     * Liste tous les tags/filtres
     */
    public function index(Request $request): JsonResponse
    {
        $query = Tag::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $tags = $query->orderBy('name')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags/{tag}",
     *     summary="Détail d'un tag",
     *     description="Récupère les informations d'un tag spécifique",
     *     operationId="getTag",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         description="ID du tag",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag récupéré avec succès",
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
     * Affiche un tag spécifique
     */
    public function show(Tag $tag): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $tag
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags/search",
     *     summary="Recherche de tags",
     *     description="Recherche de tags pour autocomplete (limite de 20 résultats)",
     *     operationId="searchTags",
     *     tags={"Tags"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Terme de recherche",
     *         required=false,
     *         @OA\Schema(type="string", example="jeu")
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
     * Recherche de tags (pour autocomplete)
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

        $tags = Tag::where('name', 'like', '%' . $query . '%')
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }
}
