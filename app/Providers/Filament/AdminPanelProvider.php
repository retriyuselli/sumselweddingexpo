<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard as PagesDashboard;
use App\Filament\Widgets\DataPembayaranOverview;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Models\Penyelenggara;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $favicon = '/storage/logo/logoswe.png';

        try {
            if (Schema::hasTable('penyelenggaras')) {
                $penyelenggara = Penyelenggara::first();
                if ($penyelenggara && $penyelenggara->favicon) {
                    $favicon = Storage::disk('public')->url($penyelenggara->favicon);
                }
            }
        } catch (\Throwable $e) {
        }

        return $panel
            ->id('admin')
            ->path('admin')
            ->default()
            ->favicon($favicon)
            ->brandName('Sumsel Wedding Expo')
            ->maxContentWidth(Width::Full)
            ->brandLogo('/storage/logo/logoswe.png')
            ->brandLogoHeight('3rem')
            ->sidebarCollapsibleOnDesktop(true)
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                PagesDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                DataPembayaranOverview::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
                BreezyCore::make()
                    ->avatarUploadComponent(fn ($fileUpload) => $fileUpload->disableLabel())
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        userMenuLabel: 'My Profile',
                        shouldRegisterNavigation: false,
                        navigationGroup: 'Account Settings',
                        hasAvatars: true,
                        slug: 'my-profile',
                    ),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
