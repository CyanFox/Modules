<?php

namespace Modules\Auth\Livewire\Components\Modals\TwoFactor;

use App\Livewire\CFModalComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Actions\Users\UpdateUserAction;
use Modules\Auth\Livewire\Account\Profile;
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

        UpdateUserAction::run(auth()->user(), [
            'two_factor_enabled' => true,
        ]);

        auth()->user()->revokeOtherSessions();

        Notification::make()
            ->title(__('auth::profile.modals.activate_two_fa.notifications.two_fa_enabled'))
            ->success()
            ->send();

        $this->dispatch('refreshProfile')->to(Profile::class);
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
        if (auth()->user()->two_factor_secret === null) {
            auth()->user()->generateTwoFASecret();
        }

        if (! $this->checkPasswordConfirmation()->passwordFunction('render')->checkPassword()) {
            return;
        }
    }

    public function render()
    {
        return view('auth::livewire.components.modals.two-factor.activate-two-fa');
    }
}
