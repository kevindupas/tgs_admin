<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
    public function index(Salon $salon): JsonResponse
    {
        $faqs = $salon->faqs()
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $faqs
        ]);
    }

    public function show(Salon $salon, Faq $faq): JsonResponse
    {
        if ($faq->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ non trouvée dans ce salon'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $faq
        ]);
    }
}
