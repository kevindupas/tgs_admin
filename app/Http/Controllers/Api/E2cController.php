<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\E2cArticle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class E2cController extends Controller
{
    /**
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
