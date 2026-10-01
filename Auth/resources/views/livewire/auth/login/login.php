<?php

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Modules\Auth\Facades\Unsplash;

new class extends Component {
    use WithRateLimiting;

    public $unsplash = [];

    public $rateLimitTime;

    public $user;

    public $username;

    public $password;

    public $rememberMe;

    public $captcha;

    public $twoFactorEnabled = false;

    public $useRecoveryCode = false;

    public $twoFactorCode;

    public function attemptLogin()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
            'rememberMe' => 'nullable|boolean',
        ]);

        if (settings('auth.login.captcha', config('auth.login.captcha'))) {
            $validator = Validator::make(['captcha' => $this->captcha], ['captcha' => 'required|captcha']);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'captcha' => __('auth::login.invalid_captcha'),
                ]);
            }
        }

        $this->checkIfUserExists($this->username);

        if (!$this->user) {
            $this->addError('username', __('auth.failed'));
            return;
        }

        if (!Hash::check($this->password, $this->user->password)) {
            activity()
                ->performedOn($this->user)
                ->causedByAnonymous()
                ->log('auth.login.failed');

            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        if ($this->user->disabled) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => __('auth::login.user_disabled'),
            ]);
        }

        Auth::login($this->user, $this->rememberMe);

        activity()
            ->performedOn($this->user)
            ->causedByAnonymous()
            ->log('auth.login');

        if (settings('auth.login.redirect')) {
            $this->redirect(settings('auth.login.redirect'));
        }

        redirect()->intended();
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

        $this->user = userModel()->where('username', $username)->first();

        $this->resetErrorBag('username');
    }

    public function changeLanguage($language)
    {
        if ($language === request()->cookie('language')) {
            return;
        }
        cookie()->queue(cookie()->forget('language'));
        cookie()->queue(cookie()->forever('language', $language));

        $this->redirect(url()->previous());
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
            ->title(__('auth::login.tab_title'));
    }
};
