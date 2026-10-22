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
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }

    public function boot(): void
    {
        if (!app()->runningInConsole()) {
            LaraHooks::listen('s.dashboard.profile.items', function () {
                if (auth()->check() && auth()->user()->can('admin.dashboard')) {
                    return Blade::render('<x-dashboard::profile.item icon="icon-wrench" route="admin.dashboard">' . __('admin::admin.admin') . '</x-dashboard::profile.item>');
                }
                return null;
            }, 15);
        }

        parent::boot();
    }
}
