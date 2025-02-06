<?php

namespace App\Providers\Filament;

use App\Routes;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Widgets\DevInfoWidget;
use Filament\PanelProvider;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use App\Filament\Pages\Settings;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Http\Middleware\SecurityHeaders;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use TomatoPHP\FilamentNotes\FilamentNotesPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use TomatoPHP\FilamentMediaManager\FilamentMediaManagerPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->spa()
            ->id('admin')
            ->path('')
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->defaultThemeMode(ThemeMode::Dark)
            ->colors([
                'primary' => '#179bef',
            ])
            ->favicon('storage/panel_assets/favicon.png')
            ->profile(isSimple: false)
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([

            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make('Gerar Pronto')
                    ->url('/gerar/pronto', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-presentation-chart-line')
                    ->sort(6),
                NavigationItem::make('Orientações')
                    ->url('/storage/panel_assets/orientation.pdf', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-book-open')
                    ->group('Ferramentas')
                    ->sort(2)
            ])
            ->plugins([
                ActivitylogPlugin::make()
                    ->label('Log')
                    ->pluralLabel('Logs')
                    ->navigationIcon('heroicon-o-shield-check')
                    ->navigationCountBadge(true)
                    ->navigationGroup('Ferramentas'),
                FilamentMediaManagerPlugin::make()
                    ->allowSubFolders(),
                FilamentNotesPlugin::make()
                    ->useStatus()
                    ->useGroups()
                    ->useShareLink()
                    ->useChecklist()
                    ->useUserAccess()
            ])
            ->breadcrumbs(false)
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
