<?php

namespace Modules\AuthModule\Livewire\Auth;

use App\Facades\Utils\UnsplashManager;
use App\Services\LWComponent;
use Carbon\Carbon;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Mail\ForgotPasswordMail;
use Modules\AuthModule\Models\User;
use Modules\AuthModule\Rules\Password;

class ForgotPassword extends LWComponent
{
    use WithRateLimiting;

    public $unsplash;

    public $rateLimitTime;

    public $email;

    public $resetToken;

    public $password;

    public $passwordConfirmation;

    public $captcha;

    public function resetPassword()
    {
        if ($this->setRateLimit()) {
            return;
        }

        $this->validate([
            'password' => ['required', new Password],
            'passwordConfirmation' => 'required|same:password',
        ]);

        $user = User::where('password_reset_token', $this->resetToken)->first();

        if ($user == null) {
            Notification::make()
                ->title(__('authmodule::auth.forgot_password.notifications.reset_token_invalid'))
                ->danger()
                ->send();

            $this->redirect(route('auth.forgot-password', ''), navigate: true);

            return;
        }

        if ($user->password_reset_expiration < Carbon::now()) {
            Notification::make()
                ->title(__('authmodule::auth.forgot_password.notifications.reset_token_expired'))
                ->danger()
                ->send();

            $this->redirect(route('auth.forgot-password', ''), navigate: true);

            return;
        }

        try {
            $user->update([
                'password' => Hash::make($this->password),
                'password_reset_token' => null,
                'password_reset_expiration' => null,
            ]);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('authmodule::auth.forgot_password.notifications.password_reset'))
            ->success()
            ->send();

        $this->redirect(route('auth.login'), navigate: true);
    }

    public function sendResetLink()
    {
        if ($this->setRateLimit()) {
            return;
        }

        $this->validate([
            'email' => 'required|email',
        ]);

        if (setting('authmodule.enable.captcha')) {
            $validator = Validator::make(['captcha' => $this->captcha], ['captcha' => 'required|captcha']);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'captcha' => __('validation.custom.invalid_captcha'),
                ]);
            }
        }

        $user = UserManager::findUserByEmail($this->email);

        if ($user == null) {
            $this->addError('email', __('authmodule::auth.forgot_password.email_not_found'));

            return;
        }

        try {
            $user->update([
                'password_reset_token' => Str::random(32),
                'password_reset_expiration' => Carbon::now()->addHours(24),
            ]);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        $resetLink = route('auth.forgot-password', [$user->password_reset_token]);

        $mail = new ForgotPasswordMail($user->email, $user->username, $user->first_name, $user->last_name, $resetLink);

        Mail::send($mail);

        Notification::make()
            ->title(__('authmodule::auth.forgot_password.notifications.reset_email_sent'))
            ->success()
            ->send();
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
        if ($this->resetToken) {
            $user = User::where('password_reset_token', $this->resetToken)->first();

            if ($user == null) {
                Notification::make()
                    ->title(__('authmodule::auth.forgot_password.notifications.reset_token_invalid'))
                    ->danger()
                    ->send();

                $this->redirect(route('auth.forgot-password', ''), navigate: true);

                return;
            }

            if ($user->password_reset_expiration < Carbon::now()) {
                Notification::make()
                    ->title(__('authmodule::auth.forgot_password.notifications.reset_token_expired'))
                    ->danger()
                    ->send();

                $this->redirect(route('auth.forgot-password', ''), navigate: true);

                return;
            }
        }

        $unsplash = UnsplashManager::returnBackground();

        $this->unsplash = $unsplash;

        if ($unsplash['error'] != null) {
            $this->log($unsplash['error'], 'error');
        }

    }

    public function render()
    {
        return $this->renderView('authmodule::livewire.auth.forgot-password', __('authmodule::auth.forgot_password.tab_title'));
    }
}
