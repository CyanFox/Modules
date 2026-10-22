<?php

namespace Modules\Dashboard\Providers;

use Illuminate\Support\Facades\Blade;
use Nwidart\Modules\Support\ModuleServiceProvider;
use RealZone22\LaraHooks\Facades\LaraHooks;

class DashboardServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Dashboard';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'dashboard';

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
            LaraHooks::listen('s.admin.profile.items', function () {
                return Blade::render('<x-admin::profile.item icon="icon-house" route="dashboard">' . __('admin::admin.home') . '</x-admin::profile.item>');
            }, 10);
        }

        parent::boot();
    }
}
