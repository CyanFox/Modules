<?php

namespace Modules\Auth\Livewire\Account;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Facades\UnsplashManager;

class ForceActivateTwoFactor extends CFComponent
{
    use WithCustomLivewireException;

    public $unsplash;

    public $currentPassword;

    public $twoFactorCode;

    public $recoveryCodes;

    public function activateTwoFA()
    {
        $this->validate([
            'currentPassword' => 'required',
            'twoFactorCode' => 'required|digits:6',
        ]);

        if (! Hash::check($this->currentPassword, auth()->user()->password)) {
            $this->addError('currentPassword', __('auth.password'));

            return;
        }

        if (! auth()->user()->checkTwoFACode($this->twoFactorCode, false)) {
            throw ValidationException::withMessages(['twoFactorCode' => __('auth::force.activate_two_factor.invalid_two_factor_code')]);
        }

        $this->recoveryCodes = auth()->user()->generateRecoveryCodes();

        auth()->user()->update(['two_factor_enabled' => true]);

        auth()->user()->revokeOtherSessions();
    }

    public function downloadRecoveryCodes()
    {
        return response()->streamDownload(function () {
            echo implode(PHP_EOL, $this->recoveryCodes);
        }, 'recovery-codes-'.auth()->user()->username.'.txt');
    }

    public function finish()
    {
        auth()->user()->update(['force_activate_two_factor' => false]);
        auth()->logout();

        Notification::make()
            ->title(__('auth::force.activate_two_factor.notifications.two_fa_enabled'))
            ->success()
            ->send();

        return redirect()->route('auth.login');
    }

    public function mount()
    {
        $this->unsplash = UnsplashManager::returnBackground();

        if ($this->unsplash['error'] !== null) {
            $this->log($this->unsplash['error'], 'error');
        }

        if (auth()->user()->two_factor_secret === null) {
            auth()->user()->generateTwoFASecret();
        }
    }

    public function render()
    {
        return $this->renderView('auth::livewire.account.force-activate-two-factor', __('auth::force.activate_two_factor.tab_title'), 'auth::components.layouts.auth');
    }
}
