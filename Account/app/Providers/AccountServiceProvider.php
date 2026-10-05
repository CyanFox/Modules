<?php

namespace Modules\Account\Providers;

use Illuminate\Support\Facades\Blade;
use Nwidart\Modules\Support\ModuleServiceProvider;
use RealZone22\LaraHooks\Facades\LaraHooks;

class AccountServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Account';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'account';

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
                return Blade::render('<x-dashboard::profile.item icon="icon-user" route="account.profile">' . __('account::profile.tab_title') . '</x-dashboard::profile.item>');
            }, 10);
        }

        parent::boot();
    }
}
