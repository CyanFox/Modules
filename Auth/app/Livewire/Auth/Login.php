<?php

namespace Modules\Auth\Livewire\Auth;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Facades\UnsplashManager;
use Modules\Auth\Models\User;

class Login extends CFComponent
{
    use WithCustomLivewireException, WithRateLimiting;

    public $unsplash = [];

    public $rateLimitTime;

    public $user;

    public $username;

    public $password;

    public $remember;

    public $captcha;

    public $twoFactorEnabled = false;

    public $useRecoveryCode = false;

    public $twoFactorCode;

    public function attemptLogin()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
            'remember' => 'nullable|boolean',
        ]);

        $this->checkIfUserExists($this->username);

        if (! $this->user) {
            return;
        }

        if (settings('auth.login.enable.captcha', config('auth.login.captcha'))) {
            $validator = Validator::make(['captcha' => $this->captcha], ['captcha' => 'required|captcha']);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'captcha' => __('auth::login.invalid_captcha'),
                ]);
            }
        }

        if (! Hash::check($this->password, $this->user->password)) {
            activity()
                ->performedOn($this->user)
                ->causedByAnonymous()
                ->log('auth.login_failed');

            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        if ($this->user->disabled) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => __('auth::login.user_disabled'),
            ]);
        }

        if ($this->user->two_factor_enabled) {
            $this->twoFactorEnabled = true;
            return;
        }

        Auth::login($this->user, $this->remember);

        activity()
            ->performedOn($this->user)
            ->causedByAnonymous()
            ->log('auth.login');

        if (settings('auth.login.redirect')) {
            $this->redirect(settings('auth.login.redirect'));
        }

        redirect()->intended();
    }

    public function checkTwoFactorCode(): void
    {
        if ($this->setRateLimit()) {
            return;
        }

        $this->checkIfUserExists($this->username);

        if (! $this->user->two_factor_enabled) {
            return;
        }

        if ($this->useRecoveryCode) {
            foreach ($this->user->recoveryCodes as $recoveryCode) {
                if (Hash::check($this->twoFactorCode, $recoveryCode->code)) {
                    $recoveryCode->delete();
                    Auth::login($this->user, $this->remember);

                    activity()
                        ->performedOn($this->user)
                        ->causedByAnonymous()
                        ->log('auth.login.recovery_code');

                    if (settings('auth.login.redirect')) {
                        $this->redirect(settings('auth.login.redirect'));
                    }

                    redirect()->intended();
                }
            }

            activity()
                ->performedOn($this->user)
                ->causedByAnonymous()
                ->log('auth.login.recovery_code.failed');

            throw ValidationException::withMessages([
                'twoFactorCode' => __('auth::login.recovery_code_invalid'),
            ]);
        }

        if ($this->user->checkTwoFACode($this->twoFactorCode)) {

            Auth::login($this->user, $this->remember);

            activity()
                ->performedOn($this->user)
                ->causedByAnonymous()
                ->log('auth.login.two_factor');

            if (settings('auth.login.redirect')) {
                $this->redirect(settings('auth.login.redirect'));
            }

            redirect()->intended();
        }

        activity()
            ->performedOn($this->user)
            ->causedByAnonymous()
            ->log('auth.login.two_factor.failed');

        throw ValidationException::withMessages([
            'twoFactorCode' => __('auth::login.two_factor_code_invalid'),
        ]);
    }

    public function checkIfUserExists($username)
    {
        $this->user = null;
        if (blank($username)) {
            return;
        }

        if ($this->setRateLimit()) {
            return;
        }

        $this->validate([
            'username' => 'required|exists:users,username',
        ], [
            'username.exists' => __('auth::login.user_not_found'),
        ]);

        $this->user = User::where('username', $username)->first();
        $this->resetErrorBag('username');
    }

    public function changeLanguage($language)
    {
        if ($language === request()->cookie('language')) {
            return;
        }
        cookie()->queue(cookie()->forget('language'));
        cookie()->queue(cookie()->forever('language', $language));

        $this->redirect(route('auth.login'));
    }

    public function setRateLimit(): bool
    {
        try {
            $this->rateLimit(settings('auth.login.rate_limit', config('auth.login.rate_limit')));
        } catch (TooManyRequestsException $exception) {
            $this->rateLimitTime = $exception->secondsUntilAvailable;

            return true;
        }

        return false;
    }

    public function mount()
    {
        $this->unsplash = UnsplashManager::returnBackground();

        if ($this->unsplash['error'] !== null) {
            $this->log($this->unsplash['error'], 'error');
        }
    }

    public function render()
    {
        return $this->renderView('auth::livewire.auth.login', __('auth::login.tab_title'), 'auth::components.layouts.auth');
    }
}
