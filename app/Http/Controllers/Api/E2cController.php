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
     * Contenu E2C d'un salon
     */
    public function content(Salon $salon): JsonResponse
    {
        if (!$salon->e2c) {
            return response()->json([
                'success' => false,
                'message' => 'E2C non activé pour ce salon'
            ], 404);
        }

        $e2cContent = $salon->e2cContent;

        return response()->json([
            'success' => true,
            'data' => $e2cContent
        ]);
    }

    /**
     * Tous les articles E2C d'un salon
     */
    public function articles(Request $request, Salon $salon): JsonResponse
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

        $articles = $query->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Articles du jury E2C
     */
    public function jury(Salon $salon): JsonResponse
    {
        if (!$salon->e2c) {
            return response()->json([
                'success' => false,
                'message' => 'E2C non activé pour ce salon'
            ], 404);
        }

        $jury = $salon->e2cJury()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $jury
        ]);
    }

    /**
     * Articles des participants E2C
     */
    public function participants(Salon $salon): JsonResponse
    {
        if (!$salon->e2c) {
            return response()->json([
                'success' => false,
                'message' => 'E2C non activé pour ce salon'
            ], 404);
        }

        $participants = $salon->e2cParticipants()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $participants
        ]);
    }

    /**
     * Article E2C spécifique
     */
    public function article(Salon $salon, E2cArticle $e2cArticle): JsonResponse
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
