<?php

namespace Modules\Auth\Livewire\Components\Modals\TwoFactor;

use App\Livewire\CFModalComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Traits\WithPasswordConfirmation;

class ActivateTwoFA extends CFModalComponent
{
    use WithCustomLivewireException, WithPasswordConfirmation;

    public $twoFactorCode;

    public $recoveryCodes;

    public function activateTwoFA()
    {
        if (! $this->hasPasswordConfirmedSession()) {
            return;
        }

        $this->validate([
            'twoFactorCode' => 'required|digits:6',
        ]);

        if (! auth()->user()->checkTwoFACode($this->twoFactorCode, false)) {
            throw ValidationException::withMessages(['twoFactorCode' => __('auth::profile.modals.activate_two_fa.invalid_two_factor_code')]);
        }

        $this->recoveryCodes = auth()->user()->generateRecoveryCodes();

        auth()->user()->update(['two_factor_enabled' => true]);

        auth()->user()->revokeOtherSessions();

        Notification::make()
            ->title(__('auth::profile.modals.activate_two_fa.notifications.two_fa_enabled'))
            ->success()
            ->send();
    }

    public function downloadRecoveryCodes()
    {
        if (! $this->hasPasswordConfirmedSession()) {
            return;
        }

        return response()->streamDownload(function () {
            echo implode(PHP_EOL, $this->recoveryCodes);
        }, 'recovery-codes-'.auth()->user()->username.'.txt');
    }

    public function regenerateRecoveryCodes()
    {
        if (! $this->hasPasswordConfirmedSession()) {
            return;
        }

        $this->recoveryCodes = auth()->user()->generateRecoveryCodes();
    }

    public function closeModal(): void
    {
        $this->redirect(route('account.profile'), true);
    }

    public function mount()
    {
        if (! $this->checkPasswordConfirmation()->passwordMethod('render')->checkPassword()) {
            return;
        }
    }

    public function render()
    {
        return view('auth::livewire.components.modals.two-factor.activate-two-fa');
    }
}
