<?php

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Modules\Auth\Facades\Unsplash;
use Modules\Core\Models\User;

new class extends Component {
    use WithRateLimiting;

    public $unsplash = [];
    public $rateLimitTime;
    public $captcha;
    public $firstName;
    public $lastName;
    public $username;
    public $email;
    public $password;
    public $passwordConfirmation;

    public function register()
    {
        $this->validate([
            'firstName' => 'nullable|string|max:255',
            'lastName' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'string', Password::defaults()],
        ]);

        if ($this->setRateLimit()) {
            return;
        }

        if (settings('auth.register.captcha', config('auth.register.captcha'))) {
            $validator = Validator::make(['captcha' => $this->captcha], ['captcha' => 'required|captcha']);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'captcha' => __('auth::register.invalid_captcha'),
                ]);
            }
        }

        User::create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        Toaster::success(__('auth::register.notifications.registered'));

        $this->redirect(route('auth.login'), true);
    }

    public function changeLanguage($language)
    {
        if ($language === request()->cookie('language')) {
            return;
        }
        cookie()->queue(cookie()->forget('language'));
        cookie()->queue(cookie()->forever('language', $language));

        $this->redirect(url()->previous(), true);
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
        $this->unsplash = Unsplash::returnBackground();
    }

    public function render()
    {
        return $this->view()
            ->layout('auth::layouts.guest')
            ->title(__('auth::register.tab_title'));
    }
};
