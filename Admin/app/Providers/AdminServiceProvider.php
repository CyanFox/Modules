<?php

namespace Modules\Admin\Providers;

use App\Http\ViewIntegrationManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Spatie\Permission\Models\Permission;

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

        View::composer('*', function ($view) {
            if (!$this->integrationsAdded) {
                if (auth()->check() && auth()->user()->can('admin.dashboard')) {
                    ViewIntegrationManager::add('dashboard.profile.items',
                        '<x-dashboard::profile-item icon="icon-wrench" label="' . __('admin::navigation.admin') . '" route="admin.dashboard" :external="true"/>');
                }
                $this->integrationsAdded = true;
            }

            Cache::rememberForever('admin.permissions', function () {
                $permissions = [
                    'admin.dashboard',

                    'admin.users',
                    'admin.users.create',
                    'admin.users.update',
                    'admin.users.delete',

                    'admin.groups',
                    'admin.groups.create',
                    'admin.groups.update',
                    'admin.groups.delete',

                    'admin.permissions',
                    'admin.permissions.create',
                    'admin.permissions.update',
                    'admin.permissions.delete',

                    'admin.settings',
                    'admin.settings.update',
                    'admin.settings.modules',
                    'admin.settings.editor',

                    'admin.modules',
                    'admin.modules.install',
                    'admin.modules.disable',
                    'admin.modules.enable',
                    'admin.modules.delete',
                ];

                foreach ($permissions as $permission) {
                    Permission::findOrCreate($permission);
                }

                return true;
            });
        });
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
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
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
                    $relativePath = str_replace($configPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $configKey = $this->nameLower . '.' . str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $relativePath);
                    $key = ($relativePath === 'config.php') ? $this->nameLower : $configKey;

                    $this->publishes([$file->getPathname() => config_path($relativePath)], 'config');
                    $this->mergeConfigFrom($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower . '-module-views']);

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

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->nameLower)) {
                $paths[] = $path . '/modules/' . $this->nameLower;
            }
        }

        return $paths;
    }
}
