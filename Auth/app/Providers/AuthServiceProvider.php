<?php

declare(strict_types=1);

namespace Modules\Auth\Providers;

use Illuminate\Validation\Rules\Password;
use Modules\Auth\Http\Middleware\Authenticate;
use Modules\Auth\Http\Middleware\CheckLanguage;
use Nwidart\Modules\Support\ModuleServiceProvider;

class AuthServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Auth';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'auth';

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
        if (!app()->runningInConsole()) {
            Password::defaults(function () {
                $password = Password::min(settings('auth.password.minimum_length'));

                if (settings('auth.password.require.numbers')) {
                    $password = $password->numbers();
                }

                if (settings('auth.password.require.special_characters')) {
                    $password = $password->symbols();
                }

                if (settings('auth.password.require.uppercase_letters')) {
                    $password = $password->mixedCase();
                }

                if (settings('auth.password.require.lowercase_letters')) {
                    $password = $password->letters();
                }

                if (settings('auth.password.require.uncompromised')) {
                    $password = $password->uncompromised();
                }

                return $password;
            });
        }

        parent::boot();
    }
}
