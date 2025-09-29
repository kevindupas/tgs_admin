<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    /**
     * Liste toutes les disponibilités d'un salon
     */
    public function index(Salon $salon): JsonResponse
    {
        $availabilities = $salon->availabilities()
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availabilities
        ]);
    }

    public function show(Salon $salon, Availability $availability): JsonResponse
    {
        if ($availability->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Disponibilité non trouvée dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    public function articles(Salon $salon, Availability $availability): JsonResponse
    {
        if ($availability->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Disponibilité non trouvée dans ce salon'
            ], 404);
        }

        $articles = $salon->articles()
            ->wherePivot('availability_id', $availability->id)
            ->wherePivot('is_published', true)
            ->orderByPivot('display_order', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }
}
