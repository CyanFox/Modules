<?php

namespace Modules\Admin\Providers;

use App\Http\ViewIntegrationManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AdminServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Admin';

    protected string $nameLower = 'admin';

    protected bool $integrationsAdded = false;

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));

        if (! app()->runningInConsole()) {
            View::composer('*', function ($view) {
                if (! $this->integrationsAdded) {
                    if (auth()->check() && auth()->user()->can('admin.dashboard')) {
                        ViewIntegrationManager::add('dashboard.profile.items',
                            '<x-dashboard::profile-item icon="icon-wrench" label="'.__('admin::navigation.admin').'" route="admin.dashboard" :external="true"/>');
                    }
                    $this->integrationsAdded = true;
                }
            });
        }

        if (! app()->runningInConsole()) {
            app()->booted(function () {
                $spotlight = app('spotlight');

                $spotlight->addItem([
                    'title' => 'admin::spotlight.users',
                    'icon' => 'icon-users',
                    'url' => route('admin.users'),
                    'permissions' => 'admin.users',
                    'module' => 'admin::spotlight.module.admin',
                ]);

                $spotlight->addItem([
                    'title' => 'admin::spotlight.groups',
                    'icon' => 'icon-shield',
                    'url' => route('admin.groups'),
                    'permissions' => 'admin.groups',
                    'module' => 'admin::spotlight.module.admin',
                ]);

                $spotlight->addItem([
                    'title' => 'admin::spotlight.permissions',
                    'icon' => 'icon-key-round',
                    'url' => route('admin.permissions'),
                    'permissions' => 'admin.permissions',
                    'module' => 'admin::spotlight.module.admin',
                ]);

                $spotlight->addItem([
                    'title' => 'admin::spotlight.settings',
                    'icon' => 'icon-settings',
                    'url' => route('admin.settings'),
                    'permissions' => 'admin.settings',
                    'module' => 'admin::spotlight.module.admin',
                ]);

                $spotlight->addItem([
                    'title' => 'admin::spotlight.modules',
                    'url' => route('admin.modules'),
                    'icon' => 'icon-package',
                    'permissions' => 'admin.modules',
                    'module' => 'admin::spotlight.module.admin',
                ]);

                $spotlight->addItem([
                    'title' => 'admin::spotlight.logs',
                    'url' => route('admin.activity'),
                    'icon' => 'icon-eye',
                    'permissions' => 'admin.activity',
                    'module' => 'admin::spotlight.module.admin',
                ]);

                if (settings('admin.spotlight.show_each')) {
                    foreach (Cache::remember('admin.spotlight.users', 120, function () {
                        return User::all();
                    }) as $user) {
                        $spotlight->addItem([
                            'title' => $user->username,
                            'description' => $user->first_name.' '.$user->last_name,
                            'icon' => 'icon-user',
                            'url' => route('admin.users.update', $user->id),
                            'permissions' => 'admin.users.update',
                            'module' => 'admin::spotlight.module.users',
                        ]);
                    }

                    foreach (Cache::remember('admin.spotlight.groups', 120, function () {
                        return Role::all();
                    }) as $group) {
                        $spotlight->addItem([
                            'title' => $group->name,
                            'description' => $group->guard_name,
                            'icon' => 'icon-shield',
                            'url' => route('admin.groups.update', $group->id),
                            'permissions' => 'admin.groups.update',
                            'module' => 'admin::spotlight.module.groups',
                        ]);
                    }
                }

                $spotlight->addStaticItem([
                    'title' => 'admin::spotlight.dashboard',
                    'icon' => 'icon-settings',
                    'url' => route('admin.dashboard'),
                    'permissions' => 'admin.dashboard',
                    'module' => 'admin::spotlight.module.admin',
                ]);
            });
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        $componentNamespace = $this->module_namespace($this->name, $this->app_path(config('modules.paths.generator.component-class.path')));
        Blade::componentNamespace($componentNamespace, $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $relativeConfigPath = config('modules.paths.generator.config.path');
        $configPath = module_path($this->name, $relativeConfigPath);

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $relativePath = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $configKey = $this->nameLower.'.'.str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $relativePath);
                    $key = ($relativePath === 'config.php') ? $this->nameLower : $configKey;

                    $this->publishes([$file->getPathname() => config_path($relativePath)], 'config');
                    $this->mergeConfigFrom($file->getPathname(), $key);
                }
            }
        }
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}
