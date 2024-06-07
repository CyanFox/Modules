<?php

namespace Modules\AuthModule\Livewire\Auth;

use App\Facades\ModuleManager;
use App\Facades\Utils\UnsplashManager;
use App\Services\LWComponent;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Modules\AuthModule\Facades\UserManager;

class Login extends LWComponent
{
    use WithRateLimiting;

    public $unsplash;

    public $user;

    public $rateLimitTime;

    #[Url]
    public $redirect;

    public $username;

    public $password;

    public $twoFactorEnabled = false;

    public $useRecoveryCode = false;

    public $twoFactorCode;

    public $rememberMe;

    public $language;

    public $captcha;

    public function attemptLogin(): void
    {
        if ($this->setRateLimit()) {
            return;
        }

        $this->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $this->checkIfUserExists($this->username);

        if (setting('authmodule.enable.captcha')) {
            $validator = Validator::make(['captcha' => $this->captcha], ['captcha' => 'required|captcha']);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'captcha' => __('validation.custom.invalid_captcha'),
                ]);
            }
        }

        if (Hash::check($this->password, $this->user->password)) {

            if ($this->user->disabled) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => __('authmodule::auth.login.user_disabled'),
                ]);
            }

            if ($this->user->two_factor_enabled) {
                Auth::logout();
                $this->twoFactorEnabled = true;

            } else {
                Auth::login($this->user, $this->rememberMe);

                if ($this->redirect) {
                    $this->redirect($this->redirect);

                    return;
                }

                if (ModuleManager::getModule('DashboardModule')->isModuleEnabled()) {
                    $this->redirect(route('dashboard'));
                } else {
                    $this->redirect('/');
                }
            }
        } else {
            throw ValidationException::withMessages([
                'password' => __('validation.current_password'),
            ]);
        }
    }

    public function checkTwoFactorCode(): void
    {
        if ($this->setRateLimit()) {
            return;
        }

        $this->checkIfUserExists($this->username);

        if (!$this->user->two_factor_enabled) {
            return;
        }

        if (UserManager::getUser($this->user)->getTwoFactorManager()->checkTwoFactorCode($this->twoFactorCode)) {

            Auth::login($this->user, $this->rememberMe);

            if ($this->redirect) {
                $this->redirect($this->redirect);

                return;
            }

            if (ModuleManager::getModule('DashboardModule')->isModuleEnabled()) {
                $this->redirect(route('dashboard'));
            } else {
                $this->redirect('/');
            }
        }

        throw ValidationException::withMessages([
            'twoFactorCode' => __('authmodule::auth.login.two_factor_code_invalid'),
        ]);
    }

    public function checkIfUserExists($username): void
    {
        $this->user = null;
        if ($username == null || $username == '') {
            return;
        }

        if ($this->setRateLimit()) {
            return;
        }

        $this->validate([
            'username' => 'required|exists:users,username',
        ]);

        $this->user = UserManager::findUserByUsername($username);
        $this->resetErrorBag('username');
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
        return $this->renderView('authmodule::livewire.auth.login', __('authmodule::auth.login.tab_title'));
    }
}
