<?php

use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent {
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

    public function confirmPassword()
    {
        $this->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($this->password, auth()->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('validation.current_password')],
            ]);
        }

        session(['core.password_confirmed_at' => time()]);
        $passwordConfirmationToken = str()->random(64);
        cache()->put(
            'core.password_confirmation.' . hash('sha256', $passwordConfirmationToken),
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
        if (!$this->title) {
            $this->title = __('core::modals.confirm_password.title');
        }

        if (!$this->description) {
            $this->description = __('core::modals.confirm_password.description');
        }
    }
};
