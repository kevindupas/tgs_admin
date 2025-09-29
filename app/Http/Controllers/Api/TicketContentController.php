<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketContent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketContentController extends Controller
{
    /**
     * Liste tous les contenus de billets
     */
    public function index(Request $request): JsonResponse
    {
        $query = TicketContent::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $ticketContents = $query->orderBy('name')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $ticketContents
        ]);
    }

    /**
     * Affiche un contenu de billet spécifique
     */
    public function show(TicketContent $ticketContent): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $ticketContent
        ]);
    }

    /**
     * Recherche de contenus de billets (pour autocomplete)
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

        $ticketContents = TicketContent::where('name', 'like', '%' . $query . '%')
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $ticketContents
        ]);
    }
}
