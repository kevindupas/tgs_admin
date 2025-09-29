<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    /**
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
