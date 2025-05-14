<?php

namespace App\Providers\Filament;

use App\Filament\Merchant\Widgets\OrdersChart;
use App\Filament\Merchant\Widgets\OrdersOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
use Outerweb\FilamentTranslatableFields\Filament\Plugins\FilamentTranslatableFieldsPlugin;
use Filament\Actions\Action as GenericAction;
use Filament\Actions\StaticAction;
use Filament\Tables\Actions\Action as TableAction;

class MerchantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('merchant')
            ->path('merchant')
            ->profile(isSimple: false)
            ->emailVerification()
            ->login()
            ->registration()
            ->passwordReset()
            ->colors([
                'primary' => Color::Red,
            ])
            ->plugins([
                FilamentTranslatableFieldsPlugin::make()
                    ->supportedLocales(config("app.available_locales")),
            ])
            ->discoverResources(in: app_path('Filament/Merchant/Resources'), for: 'App\\Filament\\Merchant\\Resources')
            ->discoverPages(in: app_path('Filament/Merchant/Pages'), for: 'App\\Filament\\Merchant\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Merchant/Widgets'), for: 'App\\Filament\\Merchant\\Widgets')
            ->widgets([
//                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
                OrdersOverview::class,
                OrdersChart::class
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
            ])
            ->bootUsing(function (Panel $panel) {
                app()->setLocale("en");

                GenericAction::configureUsing(function (GenericAction $action) {
                    $this->configureAction($action->getName(), $action);
                });
                TableAction::configureUsing(function (TableAction $action) {
                    $this->configureAction($action->getName(), $action);
                });
                StaticAction::configureUsing(function (StaticAction $action) {
                    $this->configureAction($action->getName(), $action);
                });
            });
    }

    private function configureAction(string $name, StaticAction $action): void {
        switch ($name) {
            case 'create':
            case 'submit':
                $action
                    ->color(Color::Green)
                    ->icon('heroicon-s-plus');
                break;
            case 'save':
                $action
                    ->color(Color::Blue)
                    ->icon('heroicon-s-check');
                break;
            case 'edit':
                $action
                    ->color(Color::Blue)
                    ->icon('heroicon-s-pencil');
                break;
            case 'delete':
                $action
                    ->icon('heroicon-o-trash');
                break;
        }
    }
}
