<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Categorie;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Gestion des catégories par salon"
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/categories",
     *     summary="Liste des catégories",
     *     description="Récupère toutes les catégories d'un salon",
     *     operationId="getSalonCategories",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégories récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Invités"),
     *                     @OA\Property(property="salon_id", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * Liste toutes les catégories d'un salon
     */
    public function index(Salon $salon): JsonResponse
    {
        $categories = $salon->categories()
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/categories/{category}",
     *     summary="Détail d'une catégorie",
     *     description="Récupère les informations d'une catégorie spécifique",
     *     operationId="getSalonCategory",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="ID de la catégorie",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie récupérée avec succès",
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
     *         description="Catégorie non trouvée"
     *     )
     * )
     */
    public function show(Salon $salon, Categorie $category): JsonResponse
    {
        // Vérifier que la catégorie appartient au salon
        if ($category->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/categories/{category}/articles",
     *     summary="Articles d'une catégorie",
     *     description="Récupère tous les articles appartenant à une catégorie",
     *     operationId="getCategoryArticles",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="ID de la catégorie",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles de la catégorie récupérés",
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
     *         description="Catégorie non trouvée"
     *     )
     * )
     */
    public function articles(Salon $salon, Categorie $category): JsonResponse
    {
        if ($category->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée dans ce salon'
            ], 404);
        }

        $articles = $salon->articles()
            ->wherePivot('category_id', $category->id)
            ->wherePivot('is_published', true)
            ->orderByPivot('display_order', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }
}
