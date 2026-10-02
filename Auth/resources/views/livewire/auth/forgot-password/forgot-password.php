<?php

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Modules\Auth\Emails\ForgotPasswordMail;
use Modules\Auth\Facades\Unsplash;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Models\UserSetting;

new class extends Component {
    use WithRateLimiting;

    public $unsplash = [];

    public $username;
    public $rateLimitTime;
    public $captcha;

    #[Url]
    public $token;

    public $user;

    public $password;
    public $passwordConfirmation;

    public function resetPassword()
    {
        $this->validate([
            'password' => ['required', 'string', Password::defaults()],
            'passwordConfirmation' => 'required|string|same:password',
        ]);

        if (!$this->user || $this->setRateLimit()) {
            return;
        }

        $this->user->update([
            'password' => $this->password,
        ]);

        userSettings()->delete($this->user->id, 'auth.forgot_password.token');
        userSettings()->delete($this->user->id, 'auth.forgot_password.expiration');

        activity()
            ->performedOn($this->user)
            ->causedByAnonymous()
            ->log('auth.forgot_password.reset');

        Toaster::success(__('auth::forgot-password.notifications.reset'));

        $this->redirect(route('auth.login'), true);
    }

    public function sendResetLink()
    {
        $this->validate([
            'username' => 'required|string',
        ]);

        if ($this->setRateLimit()) {
            return;
        }

        if (settings('auth.forgot_password.captcha', config('auth.forgot_password.captcha'))) {
            $validator = Validator::make(['captcha' => $this->captcha], ['captcha' => 'required|captcha']);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'captcha' => __('auth::forgot-password.invalid_captcha'),
                ]);
            }
        }

        $user = userModel()->where('username', $this->username)->first();
        if ($user) {
            $token = Str::random(64);
            $expiration = now()->addHour();
            userSettings()->set($user->id, 'auth.forgot_password.token', $token, true, [SettingsProperty::INTERNAL->value => true, SettingsProperty::HIDDEN->value => true]);
            userSettings()->set($user->id, 'auth.forgot_password.expiration', $expiration, true, [SettingsProperty::INTERNAL->value => true, SettingsProperty::HIDDEN->value => true]);

            Mail::to($user->email)->queue(new ForgotPasswordMail($user, $token, $expiration));

            activity()
                ->performedOn($user)
                ->causedByAnonymous()
                ->log('auth.forgot_password.sent');
        }

        Toaster::success(__('auth::forgot-password.notifications.sent'));

        $this->redirect(url()->previous(), true);
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
            $this->rateLimit(settings('auth.forgot_password.rate_limit', config('auth.forgot_password.rate_limit')));
        } catch (TooManyRequestsException $exception) {
            $this->rateLimitTime = $exception->secondsUntilAvailable;

            return true;
        }

        return false;
    }

    public function mount()
    {
        if (!settings('auth.forgot_password.enabled')) {
            abort(404);
        }

        $this->unsplash = Unsplash::returnBackground();

        if ($this->token) {
            $this->user = UserSetting::where('key', 'auth.forgot_password.token')->where('value', $this->token)->first()?->user;

            if ($this->user) {
                if (carbon()->parse(userSettings('auth.forgot_password.expiration', $this->user->id))->isBefore(now())) {
                    Toaster::error(__('auth::forgot-password.notifications.invalid'));

                    userSettings()->delete($this->user->id, 'auth.forgot_password.token');
                    userSettings()->delete($this->user->id, 'auth.forgot_password.expiration');
                    $this->redirect(route('auth.forgot-password'));
                }
            } else {
                Toaster::error(__('auth::forgot-password.notifications.invalid'));
                $this->redirect(route('auth.forgot-password'));
            }
        }
    }

    public function render()
    {
        return $this->view()
            ->layout('auth::layouts.guest')
            ->title(__('auth::forgot-password.tab_title'));
    }
};
