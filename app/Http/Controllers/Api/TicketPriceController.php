<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketPrice;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;

class TicketPriceController extends Controller
{
    public function index(Salon $salon): JsonResponse
    {
        $ticketPrices = $salon->ticketPrices()
            ->orderBy('price')
            ->get()
            ->map(function ($ticketPrice) {
                // Récupérer les contenus des billets
                if (is_array($ticketPrice->contents)) {
                    $ticketPrice->ticket_contents = collect($ticketPrice->contents)
                        ->map(function ($contentId) {
                            return \App\Models\TicketContent::find($contentId);
                        })
                        ->filter()
                        ->values();
                } else {
                    $ticketPrice->ticket_contents = collect();
                }
                return $ticketPrice;
            });

        return response()->json([
            'success' => true,
            'data' => $ticketPrices
        ]);
    }

    public function show(Salon $salon, TicketPrice $ticketPrice): JsonResponse
    {
        if ($ticketPrice->salon_id !== $salon->id) {
            return response()->json([
                'success' => false,
                'message' => 'Prix de billet non trouvé dans ce salon'
            ], 404);
        }

        // Récupérer les contenus des billets
        if (is_array($ticketPrice->contents)) {
            $ticketPrice->ticket_contents = collect($ticketPrice->contents)
                ->map(function ($contentId) {
                    return \App\Models\TicketContent::find($contentId);
                })
                ->filter()
                ->values();
        } else {
            $ticketPrice->ticket_contents = collect();
        }

        return response()->json([
            'success' => true,
            'data' => $ticketPrice
        ]);
    }
}
