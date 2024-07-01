<?php

namespace Modules\OAuthModule\Providers;

use App\Facades\Utils\ViewIntegrationManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class OAuthModuleServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'OAuthModule';

    protected string $moduleNameLower = 'oauthmodule';

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

        $configValues = [
            'services.authentik.enable' => setting('oauthmodule.authentik.enable'),
            'services.authentik.base_url' => setting('oauthmodule.authentik.url'),
            'services.authentik.client_id' => setting('oauthmodule.authentik.client_id'),
            'services.authentik.client_secret' => setting('oauthmodule.authentik.client_secret'),
            'services.authentik.redirect' => setting('oauthmodule.authentik.redirect'),

            'services.github.enable' => setting('oauthmodule.github.enable'),
            'services.github.client_id' => setting('oauthmodule.github.client_id'),
            'services.github.client_secret' => setting('oauthmodule.github.client_secret'),
            'services.github.redirect' => setting('oauthmodule.github.redirect'),

            'services.google.enable' => setting('oauthmodule.google.enable'),
            'services.google.client_id' => setting('oauthmodule.google.client_id'),
            'services.google.client_secret' => setting('oauthmodule.google.client_secret'),
            'services.google.redirect' => setting('oauthmodule.google.redirect'),

            'services.discord.enable' => setting('oauthmodule.discord.enable'),
            'services.discord.client_id' => setting('oauthmodule.discord.client_id'),
            'services.discord.client_secret' => setting('oauthmodule.discord.client_secret'),
            'services.discord.redirect' => setting('oauthmodule.discord.redirect'),
        ];

        if (!config('settings.disable_db_settings') && config('app.env') !== 'testing') {
            foreach ($configValues as $key => $value) {
                Config::set($key, $value);
            }
        }

        $this->app->booted(function () {
            ViewIntegrationManager::addView('authmodule.login.footer', 'oauthmodule::oauth-buttons');
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
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

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
        $this->publishes([module_path($this->moduleName, 'config/services.php') => config_path('services.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/services.php'), 'services');
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

        $componentNamespace = str_replace('/', '\\', config('modules.namespace').'\\'.$this->moduleName.'\\'.ltrim(config('modules.paths.generator.component-class.path'), config('modules.paths.app_folder', '')));
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
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
