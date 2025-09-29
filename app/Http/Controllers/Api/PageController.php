<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    /**
     * Page Presse
     */
    public function presse(Salon $salon): JsonResponse
    {
        if (!$salon->show_presses) {
            return response()->json([
                'success' => false,
                'message' => 'Page presse non activée pour ce salon'
            ], 404);
        }

        $presse = $salon->presse;

        return response()->json([
            'success' => true,
            'data' => $presse
        ]);
    }

    /**
     * Page Photos Invités
     */
    public function photosInvites(Salon $salon): JsonResponse
    {
        if (!$salon->show_photos_invites) {
            return response()->json([
                'success' => false,
                'message' => 'Page photos invités non activée pour ce salon'
            ], 404);
        }

        $photosInvites = $salon->photosInvites;

        return response()->json([
            'success' => true,
            'data' => $photosInvites
        ]);
    }

    /**
     * Page Devenir Exposant
     */
    public function becomeExhibitor(Salon $salon): JsonResponse
    {
        if (!$salon->show_become_an_exhibitor) {
            return response()->json([
                'success' => false,
                'message' => 'Page devenir exposant non activée pour ce salon'
            ], 404);
        }

        $becomeExhibitor = $salon->becomeAnExhibitor;

        return response()->json([
            'success' => true,
            'data' => $becomeExhibitor
        ]);
    }

    /**
     * Page Devenir Staff
     */
    public function becomeStaff(Salon $salon): JsonResponse
    {
        if (!$salon->show_become_a_staff) {
            return response()->json([
                'success' => false,
                'message' => 'Page devenir staff non activée pour ce salon'
            ], 404);
        }

        $becomeStaff = $salon->becomeAStaff;

        return response()->json([
            'success' => true,
            'data' => $becomeStaff
        ]);
    }
}
