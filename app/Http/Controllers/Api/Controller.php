<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="TGS Admin API Documentation",
 *     description="Documentation de l'API REST pour le système TGS Admin - Toulouse Game Show",
 *     @OA\Contact(
 *         email="support@tgs.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Serveur de développement"
 * )
 *
 * @OA\Server(
 *     url="https://votre-domaine.com",
 *     description="Serveur de production"
 * )
 *
 * @OA\Tag(
 *     name="Salons",
 *     description="Gestion des salons"
 * )
 *
 * @OA\Tag(
 *     name="Articles",
 *     description="Gestion des articles"
 * )
 *
 * @OA\Tag(
 *     name="Catégories",
 *     description="Gestion des catégories"
 * )
 *
 * @OA\Tag(
 *     name="E2C",
 *     description="Escapade à Cosplay - Concours de cosplay"
 * )
 */
class Controller extends BaseController
{
    //
}
