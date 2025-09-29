<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PracticalInfo;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;

class PracticalInfoController extends Controller
{
    public function index(Salon $salon): JsonResponse
    {
        $practicalInfos = $salon->practicalInfos()
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $practicalInfos
        ]);
    }

    public function show(Salon $salon, PracticalInfo $practicalInfo): JsonResponse
    {
        if ($practicalInfo->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Info pratique non trouvée dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $practicalInfo
        ]);
    }
}
