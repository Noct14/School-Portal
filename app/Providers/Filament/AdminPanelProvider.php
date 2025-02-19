<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Resources\CategoryNilaiResource;
use App\Filament\Resources\ClassroomResource;
use App\Filament\Resources\DepartmentResource;
use App\Filament\Resources\HomeRoomResource;
use App\Filament\Resources\NilaiResource;
use App\Filament\Resources\PeriodeResource;
use App\Filament\Resources\StudentHasClassResource;
use App\Filament\Resources\StudentResource;
use App\Filament\Resources\SubjectResource;
use App\Filament\Resources\TeacherResource;
use App\Filament\Resources\TipeTransaksiResource;
use App\Filament\Resources\TransaksiResource;
use App\Filament\Resources\UserResource;
use App\Filament\Widgets\StatsOverview;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            // ->sidebarFullyCollapsibleOnDesktop(true)
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->brandLogoHeight('80px')
            ->favicon(asset('Image/logo.svg'))
            ->darkMode(false)
            ->breadcrumbs(false)
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Purple,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StatsOverview::class,
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
            ->viteTheme('resources/css/filament/admin/theme.css')
            // ->plugin(FilamentSpatieRolesPermissionsPlugin::make())

            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Home')
                            ->icon('heroicon-o-home')
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                            ->url(fn (): string => Dashboard::getUrl()),
                            ...UserResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Management')
                        ->items([
                            ...TransaksiResource::getNavigationItems(),
                            // ...TipeTransaksiResource::getNavigationItems(),
                            // ...DepartmentResource::getNavigationItems(),
                            ...StudentResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Akademik')
                        ->items([
                            ...CategoryNilaiResource::getNavigationItems(),
                            ...SubjectResource::getNavigationItems(),
                            ...NilaiResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Setting')
                        ->items([
                            ...PeriodeResource::getNavigationItems(),
                            ...ClassroomResource::getNavigationItems(),
                            ...StudentHasClassResource::getNavigationItems(),
                            // ...HomeRoomResource::getNavigationItems(),
                            // NavigationItem::make('Roles')
                            //     ->icon('heroicon-o-user-group')
                            //     ->isActiveWhen(fn (): bool => request()->routeIs([
                            //         'filament.admin.pages.roles.index',
                            //         'filament.admin.pages.roles.create',
                            //         'filament.admin.pages.roles.view',
                            //         'filament.admin.pages.roles.edit',
                            //         ]))
                            //     ->url(fn (): string => '/admin/roles'),
                            // NavigationItem::make('Permissions')
                            //     ->icon('heroicon-o-lock-closed')
                            //     ->isActiveWhen(fn (): bool => request()->routeIs([
                            //         'filament.admin.pages.permissions.index',
                            //         'filament.admin.pages.permissions.create',
                            //         'filament.admin.pages.permissions.view',
                            //         'filament.admin.pages.permissions.edit',
                            //     ]))
                            //     ->url(fn (): string => '/admin/permissions'),
                        ]),
                ]);
            })
            
            ;
    }

    // public function boot(): void
    // {
    //     Filament::serving(function (){
    //         Filament::registerUserMenuItems([
    //             UserMenuItem::make()
    //                 ->label('Setting')
    //                 ->url(PeriodeResource::getUrl())
    //                 ->icon('heroicon-s-cog'),
    //         ]);
    //     });
    // }
}
