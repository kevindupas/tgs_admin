<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SalonController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\PracticalInfoController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\TicketPriceController;
use App\Http\Controllers\Api\E2cController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\TicketContentController;

// Toutes les routes sont en GET et publiques
Route::prefix('api/v1')->group(function () {

    // ===================
    // SALONS
    // ===================
    Route::get('/salons', [SalonController::class, 'index']);
    Route::get('/salons/{salon}', [SalonController::class, 'show']);
    Route::get('/salons/{salon}/stats', [SalonController::class, 'stats']);

    // ===================
    // ARTICLES PAR SALON
    // ===================
    Route::get('/salons/{salon}/articles', [ArticleController::class, 'index']);
    Route::get('/salons/{salon}/articles/{article}', [ArticleController::class, 'show']);
    Route::get('/salons/{salon}/articles/featured', [ArticleController::class, 'featured']);
    Route::get('/salons/{salon}/articles/scheduled', [ArticleController::class, 'scheduled']);
    Route::get('/salons/{salon}/articles/published', [ArticleController::class, 'published']);

    // ===================
    // CATÉGORIES PAR SALON
    // ===================
    Route::get('/salons/{salon}/categories', [CategoryController::class, 'index']);
    Route::get('/salons/{salon}/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/salons/{salon}/categories/{category}/articles', [CategoryController::class, 'articles']);

    // ===================
    // DISPONIBILITÉS PAR SALON
    // ===================
    Route::get('/salons/{salon}/availabilities', [AvailabilityController::class, 'index']);
    Route::get('/salons/{salon}/availabilities/{availability}', [AvailabilityController::class, 'show']);
    Route::get('/salons/{salon}/availabilities/{availability}/articles', [AvailabilityController::class, 'articles']);

    // ===================
    // FAQ PAR SALON
    // ===================
    Route::get('/salons/{salon}/faqs', [FaqController::class, 'index']);
    Route::get('/salons/{salon}/faqs/{faq}', [FaqController::class, 'show']);

    // ===================
    // INFOS PRATIQUES PAR SALON
    // ===================
    Route::get('/salons/{salon}/practical-infos', [PracticalInfoController::class, 'index']);
    Route::get('/salons/{salon}/practical-infos/{practicalInfo}', [PracticalInfoController::class, 'show']);

    // ===================
    // PARTENAIRES PAR SALON
    // ===================
    Route::get('/salons/{salon}/partners', [PartnerController::class, 'index']);
    Route::get('/salons/{salon}/partners/{partner}', [PartnerController::class, 'show']);

    // ===================
    // PRIX DES BILLETS PAR SALON
    // ===================
    Route::get('/salons/{salon}/ticket-prices', [TicketPriceController::class, 'index']);
    Route::get('/salons/{salon}/ticket-prices/{ticketPrice}', [TicketPriceController::class, 'show']);

    // ===================
    // E2C PAR SALON
    // ===================
    Route::get('/salons/{salon}/e2c', [E2cController::class, 'index']);
    Route::get('/salons/{salon}/e2c/{e2cArticle}', [E2cController::class, 'show']);

    // ===================
    // PAGES SPÉCIALES PAR SALON
    // ===================
    Route::get('/salons/{salon}/presse', [PageController::class, 'presse']);
    Route::get('/salons/{salon}/photos-invites', [PageController::class, 'photosInvites']);
    Route::get('/salons/{salon}/become-exhibitor', [PageController::class, 'becomeExhibitor']);
    Route::get('/salons/{salon}/become-staff', [PageController::class, 'becomeStaff']);

    // ===================
    // ROUTES GLOBALES (sans salon spécifique)
    // ===================
    Route::get('/articles', [ArticleController::class, 'globalIndex']);
    Route::get('/articles/{article}', [ArticleController::class, 'globalShow']);

    // ===================
    // TAGS/FILTRES GLOBAUX
    // ===================
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/tags/search', [TagController::class, 'search']);
    Route::get('/tags/{tag}', [TagController::class, 'show']);

    // ===================
    // CONTENUS DE BILLETS GLOBAUX
    // ===================
    Route::get('/ticket-contents', [TicketContentController::class, 'index']);
    Route::get('/ticket-contents/search', [TicketContentController::class, 'search']);
    Route::get('/ticket-contents/{ticketContent}', [TicketContentController::class, 'show']);
});
