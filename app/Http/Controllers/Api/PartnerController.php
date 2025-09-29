<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;

class PartnerController extends Controller
{
    public function index(Salon $salon): JsonResponse
    {
        $partners = $salon->partners()
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $partners
        ]);
    }

    public function show(Salon $salon, Partner $partner): JsonResponse
    {
        if ($partner->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Partenaire non trouvé dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $partner
        ]);
    }
}
