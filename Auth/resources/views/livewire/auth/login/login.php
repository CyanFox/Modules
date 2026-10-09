<?php

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Modules\Auth\Facades\Unsplash;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\UserSettings;
use PragmaRX\Google2FA\Google2FA;

new class extends Component {
    use WithRateLimiting;

    public $unsplash = [];

    public $rateLimitTime;

    public $user;

    public $username;

    public $password;

    public $rememberMe;

    public $captcha;

    public $mfaEnabled = false;

    public $useRecoveryCode = false;

    public $mfaCode;

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

        if (userSettings('auth.mfa.enabled', $this->user->id)) {
            $this->mfaEnabled = true;
            return;
        }

        Auth::login($this->user, $this->rememberMe);

        activity()
            ->performedOn($this->user)
            ->causedByAnonymous()
            ->log('auth.login');

        if (settings('auth.login.redirect')) {
            $this->redirect(settings('auth.login.redirect'));
        }

        $this->redirectIntended(navigate: true);
    }

    public function checkMfaCode()
    {
        $this->validate([
            'mfaCode' => 'required',
        ]);

        if ($this->setRateLimit() || !$this->user) {
            return;
        }

        $google2FA = new Google2FA();

        $secret = userSettings('auth.mfa.secret', $this->user->id);

        if (!$google2FA->verifyKey($secret, $this->mfaCode)) {
            $recoveryCodes = userSettings('auth.mfa.recovery_codes', $this->user->id);
            if (!in_array($this->mfaCode, $recoveryCodes)) {
                throw ValidationException::withMessages([
                    'mfaCode' => __('auth::login.invalid_mfa_code'),
                ]);
            }

            unset($recoveryCodes[array_search($this->mfaCode, $recoveryCodes)]);

            UserSettings::set($this->user->id, 'auth.mfa.recovery_codes', $recoveryCodes, true, [SettingsProperty::INTERNAL => true, SettingsProperty::ENCRYPTED => true]);
        }

        Auth::login($this->user, $this->rememberMe);

        activity()
            ->performedOn($this->user)
            ->causedByAnonymous()
            ->log('auth.login.mfa');

        if (settings('auth.login.redirect')) {
            $this->redirect(settings('auth.login.redirect'));
        }

        $this->redirectIntended(navigate: true);
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
            ->title(__('auth::login.tab_title'));
    }
};
