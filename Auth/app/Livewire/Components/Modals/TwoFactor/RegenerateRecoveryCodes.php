<?php

namespace Modules\Auth\Livewire\Components\Modals\TwoFactor;

use App\Livewire\CFModalComponent;
use App\Traits\WithCustomLivewireException;
use Modules\Auth\Traits\WithPasswordConfirmation;

class RegenerateRecoveryCodes extends CFModalComponent
{
    use WithCustomLivewireException, WithPasswordConfirmation;

    public $recoveryCodes = [];

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

    public function mount()
    {
        if (! $this->checkPasswordConfirmation()->passwordFunction('render')->checkPassword()) {
            return;
        }
    }

    public function render()
    {
        return view('auth::livewire.components.modals.two-factor.regenerate-recovery-codes');
    }
}
