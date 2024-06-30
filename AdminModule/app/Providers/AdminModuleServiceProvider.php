<?php

namespace Modules\AdminModule\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminModuleServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'AdminModule';

    protected string $moduleNameLower = 'adminmodule';

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
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        $this->app->booted(function () {
            if (config('app.env') == 'testing') {
                return;
            }
            if (!Cache::has('adminmodule.permissions.set')) {
                $permissions = [
                    'adminmodule.admin',
                    'adminmodule.dashboard.view',
                    'adminmodule.users.view',
                    'adminmodule.users.create',
                    'adminmodule.users.update',
                    'adminmodule.users.delete',
                    'adminmodule.groups.view',
                    'adminmodule.groups.create',
                    'adminmodule.groups.update',
                    'adminmodule.groups.delete',
                    'adminmodule.permissions.view',
                    'adminmodule.permissions.create',
                    'adminmodule.permissions.update',
                    'adminmodule.permissions.delete',
                    'adminmodule.settings.view',
                    'adminmodule.settings.update',
                    'adminmodule.settings.editor',
                    'adminmodule.settings.editor.update',
                    'adminmodule.modules.view',
                    'adminmodule.modules.disable',
                    'adminmodule.modules.enable',
                    'adminmodule.modules.install',
                    'adminmodule.modules.delete',
                    'adminmodule.modules.actions.npm',
                    'adminmodule.modules.actions.composer',
                    'adminmodule.modules.actions.migrate',
                ];

                $existingPermissionsQuery = Permission::query();
                $existingPermissions = $existingPermissionsQuery->whereIn('name', $permissions)->get()->keyBy('name');
                $newPermissions = [];

                foreach ($permissions as $permission) {
                    if (!$existingPermissions->has($permission)) {
                        $newPermissions[] = ['name' => $permission, 'module' => $this->moduleNameLower];
                    }
                }

                if (!empty($newPermissions)) {
                    Permission::insert($newPermissions);
                }
            }

            Cache::rememberForever('adminmodule.permissions.set', fn () => true);

            $role = Role::where('name', 'Super Admin')->first();
            if (!$role) {
                $role = Role::create(['name' => 'Super Admin', 'module' => $this->moduleNameLower]);
            }
            $role->syncPermissions(
                Permission::all()
            );
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
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([module_path($this->moduleName, 'config/settings.php') => config_path($this->moduleNameLower . '.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/settings.php'), $this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('modules.namespace') . '\\' . $this->moduleName . '\\' . ltrim(config('modules.paths.generator.component-class.path'), config('modules.paths.app_folder', '')));
        Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }
}
