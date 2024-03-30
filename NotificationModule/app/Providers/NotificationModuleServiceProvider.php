<?php

namespace Modules\NotificationModule\app\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire;
use Modules\NotificationModule\app\Livewire\Components\Modals\Admin\DeleteNotification;
use Modules\NotificationModule\app\Livewire\Components\Notifications;
use Modules\NotificationModule\app\Livewire\Components\Tables\Admin\NotificationsTable;

class NotificationModuleServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'NotificationModule';

    protected string $moduleNameLower = 'notificationmodule';

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

        Livewire::component('notificationmodule::components.tables.admin.notifications-table', NotificationsTable::class);
        Livewire::component('notificationmodule::components.modals.admin.delete-notification', DeleteNotification::class);
        Livewire::component('notificationmodule::components.notifications', Notifications::class);

        app('spotlight.values')->add([
            [
                'name' => __('notificationmodule::navigation.spotlight.admin.notifications.create.title'),
                'description' => __('notificationmodule::navigation.spotlight.admin.notifications.create.description'),
                'route' => 'admin.notifications.create',
                'icon' => Blade::render('<i class="icon-bell-plus text-3xl"></i>'),
                'admin' => true,
            ],
            [
                'name' => __('notificationmodule::navigation.spotlight.admin.notifications.list.title'),
                'description' => __('notificationmodule::navigation.spotlight.admin.notifications.list.description'),
                'route' => 'admin.notifications',
                'icon' => Blade::render('<i class="icon-bell text-3xl"></i>'),
                'admin' => true,
            ],

            [
                'name' => __('notificationmodule::navigation.spotlight.default.notifications.title'),
                'description' => __('notificationmodule::navigation.spotlight.default.notifications.description'),
                'route' => 'account.notifications',
                'icon' => Blade::render('<i class="icon-bell-ring text-3xl"></i>'),
                'admin' => false,
            ],
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        app('integrate.views')->add([
            [
                'location' => 'admin.sidebar',
                'section' => 'desktop.sidebar',
                'component' => 'notificationmodule::components.navigation.admin.desktop-sidebar',
            ],
            [
                'location' => 'admin.sidebar',
                'section' => 'mobile.sidebar',
                'component' => 'notificationmodule::components.navigation.admin.mobile-sidebar',
            ],

            [
                'location' => 'admin.dashboard',
                'section' => 'dashboard',
                'component' => 'notificationmodule::components.navigation.admin.dashboard',
            ],

            [
                'location' => 'admin.sidebar',
                'section' => 'mobile.navbar.quickActions',
                'component' => 'notificationmodule::components.navigation.mobile-quick-action',
            ],
            [
                'location' => 'admin.sidebar',
                'section' => 'desktop.navbar.quickActions',
                'component' => 'notificationmodule::components.navigation.desktop-quick-action',
            ],

            [
                'location' => 'sidebar',
                'section' => 'mobile.navbar.quickActions',
                'component' => 'notificationmodule::components.navigation.mobile-quick-action',
            ],
            [
                'location' => 'sidebar',
                'section' => 'desktop.navbar.quickActions',
                'component' => 'notificationmodule::components.navigation.desktop-quick-action',
            ],

            [
                'location' => 'home',
                'section' => 'home',
                'component' => 'notificationmodule::components.home-notifications',
            ],
        ]);
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
        $langPath = module_path($this->moduleName, 'lang');

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
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower.'.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('modules.namespace').'\\'.$this->moduleName.'\\'.config('modules.paths.generator.component-class.path'));
        Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
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
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
