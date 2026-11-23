<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\Blade;
use Nwidart\Modules\Support\ModuleServiceProvider;
use RealZone22\LaraHooks\Facades\LaraHooks;

class AdminServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Admin';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'admin';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     *
     * @param  $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }

    public function boot(): void
    {
        if (! app()->runningInConsole()) {
            LaraHooks::listen('s.dashboard.profile.items', function () {
                if (auth()->check() && auth()->user()->can('admin.dashboard')) {
                    return Blade::render('<x-dashboard::profile.item icon="icon-wrench" route="admin.dashboard">'.__('admin::admin.admin').'</x-dashboard::profile.item>');
                }

                return null;
            }, 15);

            app()->booted(function () {
                $spotlight = app('spotlight');
                $moduleName = modules()->getModule('Admin')->getName();

                $spotlight->addItem([
                    'title' => 'admin::users.tab_title',
                    'icon' => 'icon-users',
                    'url' => route('admin.users'),
                    'permissions' => 'admin.users',
                    'module' => $moduleName,
                ]);

                $spotlight->addItem([
                    'title' => 'admin::roles.tab_title',
                    'icon' => 'icon-shield',
                    'url' => route('admin.roles'),
                    'permissions' => 'admin.groups',
                    'module' => $moduleName,
                ]);

                $spotlight->addItem([
                    'title' => 'admin::permissions.tab_title',
                    'icon' => 'icon-key-round',
                    'url' => route('admin.permissions'),
                    'permissions' => 'admin.permissions',
                    'module' => $moduleName,
                ]);

                $spotlight->addItem([
                    'title' => 'admin::settings.tab_title',
                    'icon' => 'icon-settings',
                    'url' => route('admin.settings'),
                    'permissions' => 'admin.settings',
                    'module' => $moduleName,
                ]);

                $spotlight->addItem([
                    'title' => 'admin::modules.tab_title',
                    'url' => route('admin.modules'),
                    'icon' => 'icon-package',
                    'permissions' => 'admin.modules',
                    'module' => $moduleName,
                ]);

                $spotlight->addItem([
                    'title' => 'admin::activity.tab_title',
                    'url' => route('admin.activity'),
                    'icon' => 'icon-eye',
                    'permissions' => 'admin.activity',
                    'module' => $moduleName,
                ]);

                $spotlight->addStaticItem([
                    'title' => 'admin::dashboard.tab_title',
                    'icon' => 'icon-settings',
                    'url' => route('admin.dashboard'),
                    'permissions' => 'admin.dashboard',
                    'module' => $moduleName,
                ]);
            });
        }

        parent::boot();
    }
}
