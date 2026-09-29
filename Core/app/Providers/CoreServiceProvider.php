<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Modules\Core\Http\Middleware\Authenticate;
use Modules\Core\Http\Middleware\CheckLanguage;
use Modules\Core\Models\User;
use Nwidart\Modules\Support\ModuleServiceProvider;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
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

    protected $middlewares = [
        'language' => CheckLanguage::class,
        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
        'role_or_permission' => RoleOrPermissionMiddleware::class,
    ];

    public function boot(): void
    {
        Config::set('auth.providers.users.driver', 'eloquent');
        Config::set('auth.providers.users.model', User::class);
        Config::set('passkeys.models.authenticatable', User::class);

        $this->registerMiddleware($this->app['router']);

        if (!app()->runningInConsole()) {
            $group = Role::findOrCreate('Super Admin');
            $group->givePermissionTo(Permission::all());

            Config::set('passkeys.relying_party.name', settings('app.name', config('app.name')));
            Config::set('passkeys.relying_party.id', parse_url(settings('app.url', config('app.url')), PHP_URL_HOST));

            $configValues = [
                'app.name' => settings('app.name', config('app.name')),
                'app.url' => settings('app.url', config('app.url')),
                'app.timezone' => settings('app.timezone', config('app.timezone')),
                'app.locale' => settings('app.lang', config('app.locale')),
            ];

            if (config('app.env') !== 'testing') {
                foreach ($configValues as $key => $value) {
                    Config::set($key, $value);
                }
            }

            if (Str::startsWith(config('app.url') ?? '', 'https://') || settings('app.force_https')) {
                URL::forceScheme('https');
            }
        }

        parent::boot();
    }

    public function registerMiddleware(Router $router)
    {
        foreach ($this->middlewares as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }

        $router->middlewareGroup('auth', [
            Authenticate::class,
            CheckLanguage::class,
        ]);

        $router->pushMiddlewareToGroup('web', 'language');
    }
}
