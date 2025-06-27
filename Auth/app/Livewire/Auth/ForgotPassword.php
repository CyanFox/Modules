<?php

namespace Modules\Auth\Livewire\Auth;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Modules\Auth\Emails\ForgotPasswordMail;
use Modules\Auth\Facades\UnsplashManager;
use Modules\Auth\Models\User;
use Modules\Auth\Rules\Password;

class ForgotPassword extends CFComponent
{
    use WithCustomLivewireException, WithRateLimiting;

    public $unsplash = [];

    public $rateLimitTime;

    #[Url]
    public $passwordResetToken;

    public $user;

    public $email;

    public $password;

    public $passwordConfirmation;

    public function resetPassword()
    {
        $this->validate([
            'password' => ['required', 'same:passwordConfirmation', new Password],
            'passwordConfirmation' => 'required|same:password',
        ], [
            'password.same' => __('auth::forgot-password.password_same'),
            'passwordConfirmation.same' => __('auth::forgot-password.password_same'),
        ]);

        $this->user->update([
            'password' => Hash::make($this->password),
            'password_reset_token' => null,
            'password_reset_expiration' => null,
        ]);

        Notification::make()
            ->title(__('passwords.reset'))
            ->success()
            ->send();

        $this->redirect(route('auth.login'));
    }

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $this->checkIfUserExists($this->email);

        if (blank($this->user)) {
            return;
        }

        $resetToken = Str::random(20).time().'-'.$this->user->id;

        $this->user->update([
            'password_reset_token' => Hash::make($resetToken),
            'password_reset_expiration' => now()->addDay(),
        ]);

        activity()
            ->performedOn($this->user)
            ->causedByAnonymous()
            ->log('auth.forgot_password.sent');

        $resetLink = route('auth.forgot-password', ['passwordResetToken' => $resetToken]);

        $mail = new ForgotPasswordMail($this->user->email, $this->user->username, $this->user->first_name, $this->user->last_name, $resetLink);

        Mail::send($mail);

        Notification::make()
            ->title(__('passwords.sent'))
            ->success()
            ->send();
    }

    public function checkIfUserExists($email)
    {
        $this->user = null;
        if (blank($email)) {
            return;
        }

        if ($this->setRateLimit()) {
            return;
        }

        $this->validate([
            'email' => 'required|exists:users,email',
        ], [
            'email.exists' => __('passwords.user'),
        ]);

        $this->user = User::where('email', $email)->first();
        $this->resetErrorBag('email');
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
        if (! settings('auth.forgot_password.enable')) {
            abort(404);
        }

        $this->unsplash = UnsplashManager::returnBackground();

        if ($this->unsplash['error'] !== null) {
            $this->log($this->unsplash['error'], 'error');
        }

        if ($this->passwordResetToken) {
            try {
                $userId = explode('-', $this->passwordResetToken)[1];
                $this->user = User::findOrFail($userId);

                if (! Hash::check($this->passwordResetToken, $this->user->password_reset_token) || $this->user->password_reset_expiration < now()) {
                    Notification::make()
                        ->title(__('auth::forgot-password.notifications.invalid_reset_token'))
                        ->danger()
                        ->send();

                    $this->redirect(route('auth.forgot-password'));
                }
            } catch (Exception) {
                Notification::make()
                    ->title(__('auth::forgot-password.notifications.invalid_reset_token'))
                    ->danger()
                    ->send();

                $this->redirect(route('auth.forgot-password'));
            }
        }
    }

    public function render()
    {
        return $this->renderView('auth::livewire.auth.forgot-password', __('auth::forgot-password.tab_title'), 'auth::components.layouts.auth');
    }
}
