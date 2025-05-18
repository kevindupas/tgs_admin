<?php

namespace App\Providers\Filament;

use App\Filament\Pages\EditTenantProfilePage;
use App\Filament\Pages\RegisterTenant;
use App\Models\Salon;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use RalphJSmit\Filament\MediaLibrary\FilamentMediaLibrary;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->brandLogo(function () {
                try {
                    // Essayer de récupérer le tenant actuellement sélectionné
                    $tenant = Filament::getTenant();

                    // Si on a un tenant avec un logo, l'utiliser
                    if ($tenant && $tenant->event_logo) {
                        $media = Media::find($tenant->event_logo);
                        if ($media) {
                            return $media->getUrl('thumb');
                        }
                    }

                    // Fallback sur une image locale par défaut
                    return asset('images/tgs-logo.png'); // Assurez-vous que ce fichier existe dans public/images
                } catch (\Throwable $e) {
                    // En cas d'erreur, fallback sur une image par défaut
                    return asset('images/tgs-logo.png');
                }
            })
            ->brandLogoHeight('3rem')
            // Configuration multi-tenant
            ->tenant(Salon::class)
            ->tenantRegistration(RegisterTenant::class)
            ->tenantProfile(EditTenantProfilePage::class)
            ->tenantMenuItems([
                'register' => MenuItem::make()->label('Nouveau salon'),
            ])
            // Configuration standard
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugins([
                // Votre configuration FilamentMediaLibrary existante
                FilamentMediaLibrary::make()
                    ->mediaPickerModalWidth('7xl')
                    ->showUploadBoxByDefault()
                    ->thumbnailMediaConversion('thumb')
                    ->mediaPickerMediaConversion('thumb')
                    ->conversionResponsive(enabled: true, modifyUsing: function (Conversion $conversion) {
                        return $conversion->keepOriginalImageFormat();
                    })
                    ->conversionMedium(enabled: true, width: 800)
                    ->conversionSmall(enabled: true, width: 400)
                    ->conversionThumb(
                        enabled: true,
                        width: 600,
                        height: null,
                        modifyUsing: function (Conversion $conversion) {
                            return $conversion
                                ->keepOriginalImageFormat()
                                ->fit(Fit::Contain);
                        }
                    )
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
