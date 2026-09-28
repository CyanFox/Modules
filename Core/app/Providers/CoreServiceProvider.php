<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Config;
use Modules\Core\Models\User;
use Nwidart\Modules\Support\ModuleServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CoreServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Core';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'core';

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
        Config::set('auth.providers.users.driver', 'eloquent');
        Config::set('auth.providers.users.model', User::class);
        Config::set('passkeys.models.authenticatable', User::class);
        Config::set('passkeys.relying_party.name', settings('app.name', config('app.name')));
        Config::set('passkeys.relying_party.id', parse_url(settings('app.url', config('app.url')), PHP_URL_HOST));

        if (!app()->runningInConsole()) {
            $group = Role::findOrCreate('Super Admin');
            $group->givePermissionTo(Permission::all());
        }

        parent::boot();
    }
}
