<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Articles",
 *     description="Gestion des articles par salon"
 * )
 */
class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/articles",
     *     summary="Liste des articles d'un salon",
     *     description="Récupère tous les articles publiés d'un salon avec filtres optionnels",
     *     operationId="getSalonArticles",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filtrer par catégorie",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="availability_id",
     *         in="query",
     *         description="Filtrer par disponibilité",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="is_featured",
     *         in="query",
     *         description="Filtrer les articles mis en avant",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="is_scheduled",
     *         in="query",
     *         description="Filtrer les articles programmés",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche dans le titre",
     *         required=false,
     *         @OA\Schema(type="string", example="Zelda")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles récupérés avec succès",
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
     *                         @OA\Property(property="title", type="string"),
     *                         @OA\Property(property="content", type="string"),
     *                         @OA\Property(property="featured_image", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
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
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/articles/{article}",
     *     summary="Détail d'un article",
     *     description="Récupère les informations complètes d'un article spécifique",
     *     operationId="getSalonArticle",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="article",
     *         in="path",
     *         description="ID de l'article",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="featured_image", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article non trouvé ou non publié"
     *     )
     * )
     *
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
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/articles/featured",
     *     summary="Articles mis en avant",
     *     description="Récupère tous les articles mis en avant d'un salon",
     *     operationId="getFeaturedArticles",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles mis en avant récupérés",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="featured_image", type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     *
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
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/articles/scheduled",
     *     summary="Articles programmés",
     *     description="Récupère tous les articles programmés (avec horaire) d'un salon",
     *     operationId="getScheduledArticles",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles programmés récupérés",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="is_scheduled", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     )
     * )
     *
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
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/articles/published",
     *     summary="Articles publiés",
     *     description="Récupère tous les articles publiés d'un salon avec pagination",
     *     operationId="getPublishedArticles",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles publiés récupérés",
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
     *                         @OA\Property(property="title", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
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
     * @OA\Get(
     *     path="/api/v1/articles",
     *     summary="Liste de tous les articles",
     *     description="Récupère tous les articles (toutes éditions confondues)",
     *     operationId="getAllArticles",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche dans le titre",
     *         required=false,
     *         @OA\Schema(type="string", example="Mario")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     *
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
     * @OA\Get(
     *     path="/api/v1/articles/{article}",
     *     summary="Détail d'un article global",
     *     description="Récupère un article avec tous ses salons associés",
     *     operationId="getArticle",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="article",
     *         in="path",
     *         description="ID de l'article",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     *
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
