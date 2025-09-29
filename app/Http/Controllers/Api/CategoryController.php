<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Categorie;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
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
