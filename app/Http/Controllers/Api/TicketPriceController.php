<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketPrice;
use App\Models\Salon;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Ticket Prices",
 *     description="Gestion des prix de billets par salon"
 * )
 */
class TicketPriceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/ticket-prices",
     *     summary="Liste des prix de billets",
     *     description="Récupère tous les prix de billets d'un salon avec leurs contenus",
     *     operationId="getSalonTicketPrices",
     *     tags={"Ticket Prices"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Prix de billets récupérés avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Pass 1 jour"),
     *                     @OA\Property(property="price", type="number", example=15.00),
     *                     @OA\Property(property="contents", type="array", @OA\Items(type="integer")),
     *                     @OA\Property(property="ticket_contents", type="array", @OA\Items(type="object"))
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1/salons/{salon}/ticket-prices/{ticketPrice}",
     *     summary="Détail d'un prix de billet",
     *     description="Récupère les informations d'un prix de billet spécifique avec ses contenus",
     *     operationId="getSalonTicketPrice",
     *     tags={"Ticket Prices"},
     *     @OA\Parameter(
     *         name="salon",
     *         in="path",
     *         description="ID du salon",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="ticketPrice",
     *         in="path",
     *         description="ID du prix de billet",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Prix de billet récupéré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="price", type="number"),
     *                 @OA\Property(property="contents", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="ticket_contents", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Prix de billet non trouvé"
     *     )
     * )
     */
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
