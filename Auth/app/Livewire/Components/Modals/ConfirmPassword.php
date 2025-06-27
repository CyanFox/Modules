<?php

namespace Modules\Auth\Livewire\Components\Modals;

use App\Livewire\CFModalComponent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;

class ConfirmPassword extends CFModalComponent
{
    #[Locked]
    public $title;

    #[Locked]
    public $description;

    #[Locked]
    public $event;

    #[Locked]
    public $dispatch;

    public $password;

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

        session(['auth.password_confirmed_at' => time()]);

        if ($this->event) {
            $this->dispatch('auth.passwordConfirmed', $this->event);
        }
        if ($this->dispatch) {
            $this->dispatch(
                $this->dispatch['event'],
                ...$this->dispatch['args']
            );
        }

        $this->closeModal();
    }

    public function forceCloseModal()
    {
        $this->forceClose()->closeModal();
    }

    public function mount()
    {
        if (! $this->title) {
            $this->title = __('auth::confirm-password.title');
        }

        if (! $this->description) {
            $this->description = __('auth::confirm-password.description');
        }
    }

    public function render()
    {
        return view('auth::livewire.components.modals.confirm-password');
    }
}
