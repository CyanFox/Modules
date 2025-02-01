<?php

namespace Modules\Auth\Livewire\Auth;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Modules\Auth\Facades\SidebarManager;
use Modules\Auth\Facades\UnsplashManager;
use Modules\Auth\Models\User;
use Modules\Auth\Rules\Password;
use PragmaRX\Google2FA\Google2FA;

class Register extends CFComponent
{
    use WithRateLimiting, WithCustomLivewireException;

    public $unsplash = [];

    public $rateLimitTime;

    public $firstName;

    public $lastName;

    public $email;

    public $username;

    public $password;

    public $passwordConfirmation;

    public $captcha;

    public function register()
    {
        $this->validate([
            'firstName' => 'nullable',
            'lastName' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => ['required', 'same:passwordConfirmation', new Password],
            'passwordConfirmation' => 'required|same:password',
        ], [
            'email.unique' => __('auth::register.email_unique'),
            'username.unique' => __('auth::register.username_unique'),
            'password.same' => __('auth::register.password_same'),
            'passwordConfirmation.same' => __('auth::register.password_same'),
        ]);

        if (settings()->isTrue('auth.register.enable.captcha', config('auth.register.captcha'))) {
            $validator = Validator::make(['captcha' => $this->captcha], ['captcha' => 'required|captcha']);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'captcha' => __('auth::register.invalid_captcha'),
                ]);
            }
        }

        if ($this->setRateLimit()) {
            return;
        }

        $user = User::create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            // TODO: generate two factor secret
        ]);

        Auth::login($user);

        if (settings('auth.register.redirect')) {
            $this->redirect(settings('auth.register.redirect'));
        }

        redirect()->to('/');
    }

    public function changeLanguage($language)
    {
        if ($language == request()->cookie('language')) {
            return;
        }
        cookie()->queue(cookie()->forget('language'));
        cookie()->queue(cookie()->forever('language', $language));

        $this->redirect(route('auth.register'));
    }

    public function setRateLimit(): bool
    {
        try {
            $this->rateLimit(settings('auth.register.rate_limit', config('auth.register.rate_limit')));
        } catch (TooManyRequestsException $exception) {
            $this->rateLimitTime = $exception->secondsUntilAvailable;

            return true;
        }

        return false;
    }

    public function mount()
    {
        if (!settings('auth.register.enable')) {
            abort(404);
        }

        $this->unsplash = UnsplashManager::returnBackground();

        if ($this->unsplash['error'] != null) {
            $this->log($this->unsplash['error'], 'error');
        }
    }

    public function render()
    {
        return $this->renderView('auth::livewire.auth.register', __('auth::register.tab_title'), 'auth::components.layouts.auth');
    }
}
