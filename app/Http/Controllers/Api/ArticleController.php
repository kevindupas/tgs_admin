<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Articles d'un salon spécifique
     */
    public function index(Request $request, Salon $salon): JsonResponse
    {
        $query = $salon->articles();

        // FILTRES OBLIGATOIRES : Articles publiés ET avec date de publication dépassée
        $query->wherePivot('is_published', true)
            ->where(function ($q) {
                $q->whereNull('article_salon.published_at')
                    ->orWhere('article_salon.published_at', '<=', now());
            });

        // Filtres optionnels
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->wherePivot('category_id', $request->category_id);
        }

        if ($request->has('availability_id') && !empty($request->availability_id)) {
            $query->wherePivot('availability_id', $request->availability_id);
        }

        if ($request->has('is_featured')) {
            $query->wherePivot('is_featured', $request->boolean('is_featured'));
        }

        if ($request->has('is_scheduled')) {
            $query->wherePivot('is_scheduled', $request->boolean('is_scheduled'));
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Tri par défaut : ordre d'affichage puis date de publication
        $query->orderByPivot('display_order', 'asc')
            ->orderByPivot('published_at', 'desc');

        $articles = $query->with(['salons' => function ($query) use ($salon) {
            $query->where('salon_id', $salon->id);
        }])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Article spécifique d'un salon
     */
    public function show(Salon $salon, Article $article): JsonResponse
    {
        // Vérifier que l'article appartient bien à ce salon ET qu'il est publié avec la bonne date
        $salonArticle = $salon->articles()
            ->where('article_id', $article->id)
            ->wherePivot('is_published', true)
            ->where(function ($q) {
                $q->whereNull('article_salon.published_at')
                    ->orWhere('article_salon.published_at', '<=', now());
            })
            ->with(['salons' => function ($query) use ($salon) {
                $query->where('salon_id', $salon->id);
            }])
            ->first();

        if (!$salonArticle) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé ou non encore publié dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $salonArticle
        ]);
    }

    /**
     * Articles mis en avant d'un salon
     */
    public function featured(Salon $salon): JsonResponse
    {
        $articles = $salon->articles()
            ->wherePivot('is_featured', true)
            ->wherePivot('is_published', true)
            ->where(function ($q) {
                $q->whereNull('article_salon.published_at')
                    ->orWhere('article_salon.published_at', '<=', now());
            })
            ->orderByPivot('display_order', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Articles programmés d'un salon
     */
    public function scheduled(Salon $salon): JsonResponse
    {
        $articles = $salon->articles()
            ->wherePivot('is_scheduled', true)
            ->wherePivot('is_cancelled', false)
            ->wherePivot('is_published', true)
            ->where(function ($q) {
                $q->whereNull('article_salon.published_at')
                    ->orWhere('article_salon.published_at', '<=', now());
            })
            ->orderByPivot('published_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Articles publiés d'un salon
     */
    public function published(Salon $salon): JsonResponse
    {
        $articles = $salon->articles()
            ->wherePivot('is_published', true)
            ->where(function ($q) {
                $q->whereNull('article_salon.published_at')
                    ->orWhere('article_salon.published_at', '<=', now());
            })
            ->orderByPivot('published_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Tous les articles (global, sans salon spécifique)
     */
    public function globalIndex(Request $request): JsonResponse
    {
        $query = Article::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $articles = $query->with('salons')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Article global (avec tous ses salons associés)
     */
    public function globalShow(Article $article): JsonResponse
    {
        $article->load('salons');

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }
}
