<?php

use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use RealZone22\PenguBlade\ModalComponent;
use Spatie\LaravelPasskeys\Actions\FindPasskeyToAuthenticateAction;
use Spatie\LaravelPasskeys\Actions\GeneratePasskeyAuthenticationOptionsAction;
use Spatie\LaravelPasskeys\Support\Config;

new class extends ModalComponent
{
    #[Locked]
    public $title;

    #[Locked]
    public $description;

    #[Locked]
    public $event;

    #[Locked]
    public $dispatch;

    #[Locked]
    public $cancelEvent;

    #[Locked]
    public $modal;

    public $password;

    #[Locked]
    public bool $hasPasskeys = false;

    public function confirmPassword()
    {
        $this->validate([
            'password' => 'required|string',
        ]);

        if (! Hash::check($this->password, auth()->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('validation.current_password')],
            ]);
        }

        $this->completePasswordConfirmation();
    }

    public function generatePasskeyAuthenticationOptions(): string
    {
        if (! auth()->user()->passkeys()->exists()) {
            throw ValidationException::withMessages([
                'passkey' => [__('passkeys::passkeys.no_passkeys_registered')],
            ]);
        }

        $action = Config::getAction(
            'generate_passkey_authentication_options',
            GeneratePasskeyAuthenticationOptionsAction::class,
        );

        return $action->execute();
    }

    public function confirmPasskey(string $startAuthenticationResponse)
    {
        $passkeyOptions = session()->pull('passkey-authentication-options');

        if (blank($passkeyOptions)) {
            throw ValidationException::withMessages([
                'passkey' => [__('passkeys::passkeys.invalid')],
            ]);
        }

        $action = Config::getAction(
            'find_passkey',
            FindPasskeyToAuthenticateAction::class,
        );

        $passkey = $action->execute(
            $startAuthenticationResponse,
            $passkeyOptions,
        );

        if (
            ! $passkey
            || (string) $passkey->authenticatable_id !== (string) auth()->id()
        ) {
            throw ValidationException::withMessages([
                'passkey' => [__('passkeys::passkeys.invalid')],
            ]);
        }

        $this->completePasswordConfirmation();
    }

    private function completePasswordConfirmation()
    {
        session(['core.password_confirmed_at' => time()]);
        $passwordConfirmationToken = str()->random(64);
        cache()->put(
            'core.password_confirmation.'.hash('sha256', $passwordConfirmationToken),
            true,
            now()->addMinutes(5),
        );

        if ($this->event) {
            $this->closeModal();
            $this->dispatch('core.passwordConfirmed', $this->event);
        }
        if ($this->dispatch) {
            $this->closeModal();
            $this->dispatch(
                $this->dispatch['event'],
                ...$this->dispatch['args']
            );
        }

        if ($this->modal) {
            $arguments = $this->modal['arguments'];
            $arguments['passwordConfirmationToken'] = $passwordConfirmationToken;

            $this->dispatch(
                'openModal',
                $this->modal['component'],
                $arguments,
                $this->modal['attributes'],
            );
        }
    }

    public function cancelPasswordConfirmation()
    {
        if ($this->cancelEvent) {
            $this->dispatch('core.passwordConfirmed', $this->cancelEvent);
        }

        $this->forceClose()->closeModal();
    }

    public function forceCloseModal()
    {
        $this->cancelPasswordConfirmation();
    }

    public function mount()
    {
        if (! $this->title) {
            $this->title = __('core::modals.confirm_password.title');
        }

        if (! $this->description) {
            $this->description = __('core::modals.confirm_password.description');
        }

        $this->hasPasskeys = auth()->user()->passkeys()->exists();
    }
};
