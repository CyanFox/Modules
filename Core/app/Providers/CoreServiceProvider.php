<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Masmerise\Toaster\ToasterConfig;
use Modules\Core\Http\Middleware\Authenticate;
use Modules\Core\Http\Middleware\CheckLanguage;
use Modules\Core\Models\PersonalAccessToken;
use Modules\Core\Models\User;
use Nwidart\Modules\Support\ModuleServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
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

    public function register(): void
    {
        parent::register();

        $this->registerToasterConfig();
    }

    private function registerToasterConfig(): void
    {
        $path = module_path($this->name, 'config/toaster.php');

        if (!is_file($path)) {
            return;
        }

        $config = array_replace_recursive(
            config('toaster', []),
            require $path,
        );

        Config::set('toaster', $config);
        $this->app->instance(ToasterConfig::class, ToasterConfig::fromArray($config));
    }

    public function boot(): void
    {
        Config::set('auth.providers.users.driver', 'eloquent');
        Config::set('auth.providers.users.model', User::class);
        Config::set('passkeys.models.authenticatable', User::class);

        $this->registerMiddleware($this->app['router']);

        if (!app()->runningInConsole()) {
            $group = Role::findOrCreate('Super Admin');
            $group->givePermissionTo(Permission::all());

            $configValues = [
                'app.name' => settings('app.name', config('app.name')),
                'app.url' => settings('app.url', config('app.url')),
                'app.timezone' => settings('app.timezone', config('app.timezone')),
                'app.locale' => settings('app.lang', config('app.locale')),
                'passkeys.relying_party.name' => settings('app.name', config('app.name')),
                'passkeys.relying_party.id' => parse_url(settings('app.url', config('app.url')), PHP_URL_HOST),
            ];

            foreach ($configValues as $key => $value) {
                Config::set($key, $value);
            }

            if (Str::startsWith(config('app.url') ?? '', 'https://') || settings('app.force_https')) {
                URL::forceScheme('https');
            }
        }

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

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

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $configKey = str_replace(DIRECTORY_SEPARATOR, '.', $config);
                    $configKey = str_replace('.php', '', $configKey);

                    $key = ($config === 'config.php') ? $this->nameLower : $configKey;
                    $publishPath = ($config === 'config.php') ? config_path($this->nameLower . '.php') : config_path($config);
                    $this->publishes([$file->getPathname() => $publishPath], 'config');

                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    private function merge_config_from(string $path, string $key): void
    {
        if (app()->configurationIsCached()) {
            return;
        }

        $existing = config($key, []);
        $moduleConfig = require $path;

        config([$key => array_replace_recursive($existing, $moduleConfig)]);
    }
}
