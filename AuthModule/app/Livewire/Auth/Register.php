<?php

namespace Modules\AuthModule\Livewire\Auth;

use App\Facades\ModuleManager;
use App\Facades\Utils\UnsplashManager;
use App\Services\LWComponent;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use Modules\AuthModule\Rules\Password;

class Register extends LWComponent
{
    use WithRateLimiting;

    public $unsplash;

    public $rateLimitTime;

    public $firstName;

    public $lastName;

    public $username;

    public $email;

    public $password;

    public $passwordConfirmation;

    public function register()
    {
        if ($this->setRateLimit()) {
            return;
        }

        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'username' => 'required:unique:users,username',
            'email' => 'required|email:unique:users,email',
            'password' => ['required', new Password],
            'passwordConfirmation' => 'required|same:password',
        ]);

        if (setting('authmodule.enable.captcha')) {
            $validator = Validator::make(['captcha' => $this->captcha], ['captcha' => 'required|captcha']);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'captcha' => __('validation.custom.invalid_captcha'),
                ]);
            }
        }

        if (UserManager::findUserByEmail($this->email)) {
            $this->addError('email', __('authmodule::auth.register.email_already_taken'));

            return;
        }

        if (UserManager::findUserByUsername($this->username)) {
            $this->addError('username', __('authmodule::auth.register.username_already_taken'));

            return;
        }

        try {
            $user = User::create([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'username' => $this->username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('authmodule::auth.register.notifications.account_created'))
            ->success()
            ->send();

        Auth::login($user, true);

        if (setting('authmodule.redirects.register')) {
            $this->redirect(setting('authmodule.redirects.register'));
        }

        if (ModuleManager::getModule('DashboardModule')->isModuleEnabled()) {
            if (setting('dashboardmodule.routes.dashboard')) {
                $this->redirect(route('dashboard'));
            } else {
                $this->redirect('/');
            }
        } else {
            $this->redirect('/');
        }
    }

    public function setRateLimit(): bool
    {
        try {
            $this->rateLimit(10);
        } catch (TooManyRequestsException $exception) {
            $this->rateLimitTime = $exception->secondsUntilAvailable;

            return true;
        }

        return false;
    }

    public function mount(): void
    {

        $unsplash = UnsplashManager::returnBackground();

        $this->unsplash = $unsplash;

        if ($unsplash['error'] != null) {
            $this->log($unsplash['error'], 'error');
        }

    }

    public function render()
    {
        return $this->renderView('authmodule::livewire.auth.register', __('authmodule::auth.register.tab_title'));
    }
}
